EMP
===

A PHP Utility Framework

This framework was created and intended to be used for complex projects related to ProcessMaker (www.processmaker.com).

To use this framework with ProcessMaker, simply stick the whole framework in /opt/processmaker/workflow/public_html.


Controller
===
Create a controller file inside the user-name directory. The controller directory was named this way because of the environment in which it was created. We have more than one programmer using the framework, so as to make it easier for people to not code over others controllers we decided each programmer would get his/her own directory and simply throw their controllers in their directory.
Therefore, please name the controller whatever name you like, and find inside emp.php the coresponding constant and modify it accordingly.

You may name the controller file anything you like, but it must be ucfirst and be the same name as your controllers class name. The class name must also be ucfirst and extend the Emp class.

Config
===
Inside of app/config you will find the configuration files. You may add your specific configuration variables to the existing files or make your own. Any file inside of that directory will be automatically picked up by the framework and added to your controllers configuration variable for use inside your controller.
Please name your configuration file ucfirst and conform to the standard applied within the configuration files.

Accessing EMP From Within ProcessMaker Triggers
===
To access the EMP framework from within a trigger, you could do something like this to make it generic and easily exportable.

//Get the server type and domain name

$server = ( isset ( $_SERVER['HTTPS'] ) &&  $_SERVER['HTTPS'] == 'on' )?'https://' . $_SERVER['SERVER_NAME'] . "/" : 'http://' . $_SERVER['SERVER_NAME'] . "/";

//Concat the server type and domain name with the actual request

$_SERVER['REQUEST_URI'] = $server."emp/user-name/controller-name/function-name/var1/var3/var4/etc";

//Check to make sure that the framework has not yet been included somewhere else. This was needed by us because we 
modified the source code and added references to the EMP framework within

if ( ! function_exists( 'empGo' ) ) include( PATH_HTML.'emp/emp.php' );

//To return a result from your controller to the trigger

$result = empGo();

AJAX
===
Ajax calls may be made the same way you would with any other framework.


Send me an email to ethan@myemp.us if you wish to become a contributer or have any questions or need help.
