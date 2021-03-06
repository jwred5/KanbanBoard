<?php
/*
Copyright(c) 2012, Eckhardt Optics
Authors: Evan Oman, John Eckhardt

This is part of Bugzilla Kanban Board.

Bugzilla Kanban Board is free software: you can
redistribute it and/or modify it under the terms of the GNU
General Public License (GNU GPL) as published by the Free Software
Foundation, either version 3 of the License, or (at your option)
any later version.  The code is distributed WITHOUT ANY WARRANTY;
without even the implied warranty of MERCHANTABILITY or FITNESS
FOR A PARTICULAR PURPOSE.  See the GNU GPL for more details.

As additional permission under GNU GPL version 3 section 7, you
may distribute non-source (e.g., minimized or compacted) forms of
that code without the copy of the GNU GPL normally required by
section 4, provided you include this license notice and a URL
through which recipients can access the Corresponding Source.
 */

session_start();

if (isset($_SESSION["login"]) && isset($_SESSION["password"])) {
    die(header("location: index.php"));
}

session_write_close();
?>

<!DOCTYPE html> 
<html>
    <head>
        <title>Log in</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">        
        <link  type="text/css" href="themes/black-tie/jquery-ui.css" rel="stylesheet" />    
        <script type="text/javascript" src="js/jquery-1.7.2.js"></script>
        <script type="text/javascript" src="js/jquery.ui.core.js"></script>
        <script type="text/javascript" src="js/jquery.ui.widget.js"></script>        
        <script type="text/javascript" src="js/jquery.ui.dialog.js"></script>   
        <script type="text/javascript" src="js/jquery.ui.mouse.js"></script>
        <script type="text/javascript" src="js/jquery.ui.draggable.js"></script>
        <script type="text/javascript" src="js/jquery.ui.resizable.js"></script>
        <script type="text/javascript" src="js/jquery.ui.position.js"></script>
        <script type="text/javascript" src="js/jquery.ui.button.js"></script>
        <link type="text/css" href="css/index.css" rel="stylesheet" />
        <style type="text/css">
            #dialogLogin{
                width: auto;
            }
            body{
                width: 100%;
                height: 100%;
            }
            #loginForm input[type='text'], #loginForm input[type='password']
            {
                width: 100%;
            }

        </style>        
        <script type="text/javascript">
            var userID;
            $(document).ready(function(){ 
            
                //Creates the options dialog
                $( "#dialogLogin" ).dialog({
                    autoOpen: true,
                    resizable: false,
                    height: "auto",
                    width: 400,
                    position:  ['center','top'] ,
                    show: {
                        effect: 'blind'
                    },                   
                    modal: true,
                    closeOnEscape: false,
                    open: function(event, ui) { $(".ui-dialog-titlebar-close").hide(); }
                });
                
                //Sets the product tool tip
                $("#product").attr("title", "Enter the name of the product you want to work on exactly as it appears in Bugzilla.\n(If you leave this blank or you mistype you can reset it once the board has been loaded)")
                
                
                $("#btnSubmit").button();
            
                $("#loginForm").submit(function(e){ 
                    
                    $( "#dialogLogin" ).dialog("close");
                
                    $("body").addClass("loading");
                
                    e.preventDefault();
                
                    //An attempt to counter mousedown issue(see bug #128)
                    //$("#btnSubmit").click();
                                                
                    var login = $("#login").val();
                    var password = $("#password").val();    
                    var product = $("#product").val();    
                    
                    
                    $.ajax({
                        url: "ajax_login.php",
                        type: "POST",
                        dataType: "json",
                        data: {                                                             
                            "login": login,
                            "password":  password,
                            "product": product
                        },
                            
                        success: function(data, status){
                            if (data.result.faultString != null)
                            {
                                alert(data.result.faultString+'\nError Code: '+data.result.faultCode);
                            
                                $("body").removeClass("loading");
                                
                                $( "#dialogLogin" ).dialog("open");
                            }
                            else if (!data.result)
                            {
                                alert("Something is wrong");
                                $("body").removeClass("loading");
                                
                                $( "#dialogLogin" ).dialog("open");
                            }
                            else 
                            {
                                document.location.href = "index.php";
                            }                                                          
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            alert("There was an error:" + textStatus);
                        }
                    })
                });
                
            });
            
        </script>
    </head>
    <body>     
        <div id="dialogLogin" class="ui-dialog-content ui-widget-content"  title="Login" >
            <form id="loginForm">
                <fieldset>
                    <div>
                        <div >
                            <label for="login">User Name</label>
                            <input type="text"  name="login" id="login"  class="text ui-widget-content ui-corner-all"/>    
                        </div>
                        <div >
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password"  class="text ui-widget-content ui-corner-all" />   
                        </div>
                        <div>
                            <label for="product">Product Name</label>
                            <input  name="product" id="product"  class="text ui-widget-content ui-corner-all"  type="text"/>
                        </div>
                        <input type="submit" id="btnSubmit"value="Login" style="float: right;"/>
                    </div>
                </fieldset>
            </form>  
        </div>    
        <div class="modal"><div class="loadingLabel">Logging In</div></div>

    </body>
</html>
