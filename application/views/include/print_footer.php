        </div>
        <footer id="print-preview-footer" class="navbar navbar-default">
            <div class="container">
                <a href='http://www.msumain.edu.ph' title='Proceed to MSU Marawi Campus Website'><img id='akan-logo' src='<?php echo base_url(); ?>/assets/css/images/akan-logo.png'></a>
                <div class="text-muted" id="footer-left">
                    MSU-Akan<br />
                    This page is maintained by: Information Systems Department,
                    College of Information Technology,<br/>
                    Mindanao State University,<br/>
                    Marawi City
                    <br /><br/>
                    <b>All rights reserved &copy 2012 MSU Marawi Campus</b>
                </div>
                <div class="text-muted" id="footer-right">
                    For MSU Akan related comments and suggestions,
                    please email us at webteam@msumain.edu.ph
                    <br/><br/>
                    <p><b>Follow us</b><br/><br/>
                        <a href='https://www.facebook.com/MSUMarawi' title='Follow us on our Facebook page'><img src='<?php echo base_url(); ?>/assets/css/images/facebook.png'></a>&nbsp;
                        <a href='https://www.twitter.com/MSUMarawi' title='Follow us on our Twitter account'><img src='<?php echo base_url(); ?>/assets/css/images/twitter.png'></a>
                    </p>                        
                </div>
                <p style="clear:both;">&nbsp;</p>
            </div>
        </footer>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.easing.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#print-preview-navigation").mouseover(function(){
                    $("#print-preview-navigation h3").css({display: 'block'});
                })
                
                $("#print-preview-navigation").mouseout(function() {
                    $("#print-preview-navigation h3").css({display: 'none'});
                });
            });
        </script>
    </body>
</html>