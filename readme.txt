----------------------- Page 1-----------------------

 CustomHookPoints - CHP 

EE2 Module 

A lpha 

----------------------- Page 2-----------------------

Table of Contents 

     Foreword 
     About 
     Installation 
     How to use 
     Un-install 
     To Do 
  

Foreword 

Custom Hook Points (CHP) is a module that allows you to insert a hook into any file in  
Expression Engine2. This works by reading the file contents and inserting designated code into  
functions selected by you, the user. 
  
It is important to note that this module is not, in its self a hook. CHP does not create any hooks  
(pieces of code that run at certain junctures) it just allows you to put hook points at the start of a  
function in any file. 
  
I would suggest creating a complete back up of you site before you use this module. 

About 

CHP came about from the need to be able to create custom functionality in the existing  
ExpressionEngine2 core code (im talking core controller files with no hooks defined), however I  
realised that in order to do so I would need to continuously edit core EE2 code. 
  
I wanted to use hooks as they allowed me to store the bulk of my custom code in separate files  
- this proved a problem, i still had to manually go to the core files and add the custom hook code  
to the right function etc.This also meant a potentially long list of hooks to keep track of. 
  
The solution was to write this module, it will auto enter hook points in code where you need  
them, keep track of your hook points, remove your hook points and allow your code to be  
separated from the main core of EE2. 
  
At present the CHP module allows you to insert a hook point into any function in any file in the / 
system/expressionengine/ directory.  
  
Important Notes: 
     ● Hook points are currently only entered at the start of the function you have selected. 
     ● Every file you create a hook in is backed up in the following format:  
         origonal_file_name.ext_date_time_BACKUP, for example:  
         addons.php_2012_03_19_10_00_00_BACKUP - this is supposed to allow you to  
          manually recover if needs be. 

----------------------- Page 3-----------------------

Installation 

     1. Copy the “CustomHookPoints” folder into your root/system/expressionengine/thirdparty/  
         directory. 
    2. Login to your admin panel: http://domain/system 
     3. Once Logged in go to Add-Ons > Modules 
    4. Find the Custom Hook Points row and click “install” 
     5. Job Done. 

How to use 

Once Installed go your modules page and click on the CHP module, you will now be shown a  
list of custom hook points (if any) that you have already installed. 
  
To add a new hook point click on the “Add Hook Point” button on the right hand side (above the  
list of hooks) and use the following steps: 
     1. Enter a hook name - this must be unique and have no spaces, example: custom_hook.  
         This should be name that you will call in your hook. 
    2. Select a file to add the hook to from the list provided. 
     3. Select a function from the new drop down list that has appeared. 
    4. Hit the “create” button. 
     5. Your hook is now created.  
  
To remove a hook Open the CHP main page and click delete against the hook you wish to  
remove. 
  
Note this will delete the hook from the file but not restore the original file. 

Un-install 

To un-install go to your Modules screen and click “Remove”. This will delete all custom hook  
points that you have created but will not remove the code from the associated files. 
  

To Do 

This module is currently in development. This is a list of items that are planned for future  
releases: 
     1. Auto Detect Hook Point Status - this is for when upgrading. The module will scan  
         existing hook point locations and give a status update (Good/Bad) this feature will be  
         switchable to allow you to turn it off (i can foresee it using a load of resources). This i  
         am hoping will be good for when you have upgraded EE2 and will give you the option to  
         Auto restore all your affected hook points. 

----------------------- Page 4-----------------------

2. Manual Detect Hook Point Status - same as above but will be a menu item in the main  
     hook point table allowing you to check the status one hook at a time. 
3. Restore Previous Backup - Restore option in the main hook point table will allow you to  
     select all previous backups of the file the hook is in to restore the file to (not quite sure  
     how to tally this up with the database though). 
4. Mass Delete/Restore All - Main hook point table options to select multiple hook points  
     and either delete or restore them. 
5. Any other items brought to my attention. (bugs, feature requests etc)  
  
