#!/usr/bin/env python
# -*- coding: utf-8 -*-
import struct
import time
from datetime import datetime
import MySQLdb
from MySQLdb import Error

# define database configuration
USER = "root"
PASSWORD = "1a2t3s4PMS"
HOST = "127.0.0.1"
DB = "ats_pms"

MIN_LOAD_FREQ = 59.7 #Normal Condition

MAX_CRITICAL_FREQ = 62.4 #61.8
MIN_CRITICAL_FREQ = 57.6 #58.2

#Number of Consecutive Abnormal Occurences
num_abnormal_occurences = 0

#delay routine
delay_routine = 1

#selected source data
is_auto_load_shedding = 0
power = 0
frequency = 0
source_id = 1

#preferences
power_max = 100

#days
days = ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"]

def update_notifications(cnx,message,status):
    cursor = cnx.cursor()
    cursor.execute(("""INSERT INTO notifications(description,status) VALUES(%s,%s)"""),(message,status))
    cursor.close()
    cnx.commit()

def update_load_shedding():
    while True:
        try:
            #Connect to DB
            cnx = MySQLdb.connect(user=USER,passwd=PASSWORD,host=HOST,db=DB)
            num_abnormal_occurences = 0
            while cnx is not None:
                try:
                    #Get selected source
                    cursor = cnx.cursor()
                    query = ("SELECT source_id, frequency, power, is_auto_load_shedding FROM source WHERE is_selected = 1")
                    cursor.execute(query)
                    data = cursor.fetchone();
                    if data is not None:
                        source_id = int(data[0])
                        frequency = float(data[1])
                        power = float(data[2])
                        is_auto_load_shedding = int(data[3])
                    cursor.close()
                    cnx.commit()
                    
                    #Retrieve current settings
                    cursor = cnx.cursor()
                    query = ("SELECT s.power_max FROM settings s WHERE s.start_effectivity_date <= CURRENT_DATE ORDER BY s.start_effectivity_date DESC LIMIT 1")
                    cursor.execute(query)
                    data = cursor.fetchone();
                    if data is not None:
                        power_max = float(data[0])
                    cursor.close()
                    cnx.commit()
                        
                    #Load shedding
                    #Retrieve connected loads schedules...
                    cursor = cnx.cursor()
                    query = ("""SELECT con_load_id, schedule_day, schedule_on, schedule_off, status, DATE_FORMAT('%h:%i:%s',NOW()) as today FROM connected_load ORDER BY priority, con_load_id""")
                    cursor.execute(query)
                    con_loads = []
                    current_datetime = datetime.today();
                    current_time = current_datetime.time()
                    
                    if (is_auto_load_shedding == 1) and ((power_max < power) or ((MIN_LOAD_FREQ > frequency) and (MIN_CRITICAL_FREQ < frequency))) :
                        update_notifications(cnx,"Load shedding was initiated due to unstable frequency of power max data capping at " + datetime.datetime.now().strftime("%b %d, %Y %H:%M:%S %a"),0)
                        i = 0;
                        for (con_load_id, schedule_day, sched_on, sched_off, status, today) in cursor:
                            if i == 0 and status == 1:
                                if(num_abnormal_occurences > 2):
                                    con_loads.append([con_load_id, 0])
                                    i = 1
                                    num_abnormal_occurences = 0
                                else:
                                    num_abnormal_occurences += 1
                            elif status == 1:
                                if schedule_day is not None and schedule_day.find(days[current_datetime.weekday()])>=0:
                                    if sched_on is not None and sched_off is not None:
                                        schedule_on = (datetime.min + sched_on).time()
                                        schedule_off = (datetime.min + sched_off).time()
                                        if schedule_on < schedule_off:
                                            if(schedule_off < current_time):      
                                                con_loads.append([con_load_id, 0])
                                            elif(schedule_on < current_time):
                                                con_loads.append([con_load_id, 1])
                                        else:
                                            if(schedule_on < current_time):
                                                con_loads.append([con_load_id, 1])
                                            elif(schedule_off < current_time):
                                                con_loads.append([con_load_id, 0])
                                    else:
                                        if(sched_off is not None and (datetime.min + sched_off).time() < current_time):
                                            con_loads.append([con_load_id, 0])
                                        elif(sched_on is not None and (datetime.min + sched_on).time() < current_time):
                                            con_loads.append([con_load_id, 1])  
                    else:
                        #Do usual load scheduling
                        num_abnormal_occurences = 0
                        for (con_load_id, schedule_day, sched_on, sched_off, status, today) in cursor:
                            if schedule_day is not None and schedule_day.find(days[current_datetime.weekday()])>=0:
                                if sched_on is not None and sched_off is not None:
                                    schedule_on = (datetime.min + sched_on).time()
                                    schedule_off = (datetime.min + sched_off).time()
                                    if schedule_on < schedule_off:
                                        if(schedule_off < current_time):      
                                            con_loads.append([con_load_id, 0])
                                        elif(schedule_on < current_time) and (is_auto_load_shedding != 1):
                                            con_loads.append([con_load_id, 1])
                                    else:
                                        if(schedule_on < current_time) and (is_auto_load_shedding != 1):
                                            con_loads.append([con_load_id, 1])
                                        elif(schedule_off < current_time):
                                            con_loads.append([con_load_id, 0])
                                else:
                                    if(sched_off is not None and (datetime.min + sched_off).time() < current_time):
                                        con_loads.append([con_load_id, 0])
                                    elif(sched_on is not None and (datetime.min + sched_on).time() < current_time) and (is_auto_load_shedding != 1):
                                        con_loads.append([con_load_id, 1])
                        cursor.close()
                        cnx.commit()
                    
                    #Update connected loads
                    for i in range(0,len(con_loads)):
                        cursor = cnx.cursor()
                        cursor.execute("""UPDATE connected_load SET status = %s WHERE con_load_id = %s""",(con_loads[i][1],con_loads[i][0]))
                        cursor.close()
                        cnx.commit()
                            
                except MySQLdb.Error as err:
                    print(err)
                    continue
                except IOError as err:
                    print(err)
                    continue
                except Error as err:
                    print(err)
                    continue
                time.sleep(delay_routine)
        except MySQLdb.Error as err:
            print(err)
            continue
        except IOError as err:
            print(err)
            continue
        except Error as err:
            print(err)
            continue
        finally:
            cnx.close()
            
update_load_shedding()