import sched
import mysql.connector
import array
import time
import math
from datetime import datetime
from mysql.connector import errorcode

cnx = mysql.connector.connect(user='root',password='',host='127.0.0.1',database='ats_pms')   
instant_power = 100

s = sched.scheduler(time.time, time.sleep)
print("Start Updating Load Side Data")
def upload_data(sc):
    print("Uploading Data Every 60 seconds")
    source_id = 1
    current = 0
    voltage = 0
    power = 0
    var = 0
    frequency = 0
    status = 0
    apparent = 0
    
    cursor = cnx.cursor()
    cursor = cnx.cursor()
    query = ("SELECT s.source_id, s.current, s.voltage, s.power, s.frequency, s.status FROM source s WHERE s.is_selected = 1")
    cursor.execute(query)
    data = cursor.fetchone();
    
    if data is not None:
        source_id = data[0]
        current = data[1]
        voltage = data[2]
        power = data[3]
        frequency = data[4]
        status = data[5]
        apparent = voltage*current
        if apparent >= power: 
            var = math.sqrt(math.pow(apparent,2)-math.pow(power,2))
        else:
            var = 0
            power = 0
        
    cursor = cnx.cursor()
    cursor.execute("""INSERT load_side(source,voltage,current,power,var,frequency,status,datetime) VALUES(%s,%s,%s,%s,%s,%s,%s,NOW())""",
                   (source_id,voltage,current,power,var,frequency,status))
    cursor.close()
    cnx.commit()
    s.enter(15, 1, upload_data, (sc,))
s.enter(15, 1, upload_data, (s,))
s.run()
cnx.close();
