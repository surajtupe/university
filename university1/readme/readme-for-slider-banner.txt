|*|*********************************************************************************************************************|*|
|*|*********************************************************************************************************************|*|
|x|                                                                                                                     |x|
|x|                                                                                                                     |x|
|x|        _____         _____         ___        __        _____            ________   _________        _____          |x|
|x|       //---\\       //---\\       ||-\\       ||       //---\\          //======/   ||_______|      //---\\         |x|
|x|      ||     \\     //     \\      ||  \\      ||      //     \\        //           ||             //     \\        |x|
|x|      ||_____||    //       \\     ||   \\     ||     //       \\      ||            ||______      //       \\       |x|
|x|      ||____//    ||=========||    ||    \\    ||    ||=========||     ||            ||______|    ||=========||      |x|
|x|      ||          ||=========||    ||     \\   ||    ||=========||     \\            ||           ||=========||      |x|
|x|      ||          ||         ||    ||      \\  ||    ||         ||      \\_______    ||_______    ||         ||      |x|
|x|      ||          ||         ||    ||       \\_||    ||         ||       \=======\   ||_______|   ||         ||      |x|
|x|                                                                                                                     |x|
|x|                                                                                                                     |x|
|*|*********************************************************************************************************************|*|
|*|*********************************************************************************************************************|*|

   PIPL Code Library - Slider Banner Module for CI(codeigniter).
   Author - Anuj Tyagi
   Purpose - The module will manage backend "Slider Banner Module" functionality for CI(codeigniter).

   --------------------------------------
     Ques:- What is "Slider Banner" ?

     Ans:- "Slider Banner" is a floating animated banner on front end of the website, generally it appears on "Home Page" of the site.
       Most of sliding banners rotate images, some of them contains image + video along with description (a text paragraph). 
       This is widly used concept nowdays, and required in almost every web project. Also, sliding banner may be reflected in several ways 
       including image sizes, image thumbnails, effects etc.
   ---------------------------
   1. Installation Notes
   ---------------------------
   In order to use Slider Banner module CI , Below are steps.

	Step 1 : Create the necessary data structure required for the project, the db_sql found in the file db_sql -> db-sql-file-for-slider-banner, Import this file in your database. Please note, the suffix  must match with your project's suffix if not matched, you may need to change the suffix.
			
			e.q. 1.create database p778
			     2.Carefully Find and replace PIPL_  with  p778_ in the sql file
			     3.Copy sql and fire query in datatbase created	
	
	Step 2 : Copy the routes Slider Banner routes from the file	pipl-code-library-ci -> application -> config -> routes.php and 

		Paste those paths to your project route.

	Step 3 : Copy following model from  pipl-code-library-ci -> application -> models folder to your project models folder eq to P778-> application -> models 	folder

		
		1.  common_model.php
		2. slider_banner_model.php

        Stpe 4 : Step 3 : Copy following controller from  pipl-code-library-ci -> application -> controllers folder to your project controller folder e.q. to P778-> application -> controllers folder
		
		for front end side:-
                "home.php"  controller is get used.
                for back end side:-
                "slider_banner.php"  controller is get used.

	Step 5 :Copy following folders and file from  pipl-code-library-ci -> application -> view -> backend folder to your project view backend folder e.q. to P778-> application -> view -> backend folder
		
		1.slider-banner

		
	Step 6 : Set your .htaccess rule correctly for the project directory


	Step 7 : check your Config parameter into  for the ci application is correctly set i.e into the config.php and database.php file found into the pipl-code-library-ci -> application -> config folder.

	
	Step 8 : Hit the admin login url in browser window
		
		  e.g http://192.168.2.41/p778/backend/login 
		
			Enter user name : admin
			Password : admin
              for front end :-Hit below url for e.g
            e.g http://192.168.2.41/p778/

	Step 9 : Your slider banner module for project is ready.Check all Functionalities are working properly.


	
   ---------------------------
   2. Slider Banner Controller Logic
   ---------------------------

   All the Models, controller and View are self explanatory having all comments associated with respective method on top of the method and inside method.
   
    Note :-  Please check the you have all required css and js files which are required for the slider banner module check the media/front/js/slider-banner & media/front/css/slider-banner folders for same.
 


|*|*********************************************************************************************************************|*|
|x|                                                                                                                     |x|
|x|                                          PIPL Slider Banner Module For CI - Readme.txt    for backend                                          |x|
|x|                                                                                                                     |x|
|*|*********************************************************************************************************************|*|






