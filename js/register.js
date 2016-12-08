/* ---------------------------------------------
register form outher oguzhan
 --------------------------------------------- */
$(document).ready(function(){
    $("#saveBtn").click(function(){
		var user_name = $('input[name=username]').val();
        var user_email = $('input[name=email]').val();
        var user_password = $('input[name=password]').val();
        var user_repassword = $('input[name=re-password]').val();
        //simple validation at client's end
        //we simply change border color to red if empty field using .css()
        var proceed = true;
        if (user_name === "") {
            $('input[name=username]').css('border-color', '#e41919');
            proceed = false;
        }
        if (user_email == "") {
            $('input[name=email]').css('border-color', '#e41919');
            proceed = false;
        }
        
        if (user_password == "") {
            $('input[name=password]').css('border-color', '#e41919');
            proceed = false;
        }
        
        if (user_repassword == "") {
            $('input[name=re-password]').css('border-color', '#e41919');
            proceed = false;
        }
        if(user_password!=user_repassword){
			$('input[name=password]').css('border-color', '#e41919');
			$('input[name=re-password]').css('border-color', '#e41919');
			proceed = false;
		}
        //everything looks good! proceed...
        if (proceed) {
			
		console.log('asdasdas');
		var url='index.php?p=1&a=2';
		var post_data = {
				d:{              
					'username': user_name,
					'eposta': user_email,
					'password': user_password,
					'repassword':user_repassword
				}
			};
			//Ajax post data to server
            $.post(url,post_data, function(response){
				console.log(response);
                //load json data from server and output message     
                if (response.type == 'error') {
                    output =  swal("Oops!",""+response.text+"","error");
					
                }
                else {
                
                    output = swal({
									title: "Sweet!",
									text: "Here's a custom image.",
									imageUrl: "images/thumbs-up.jpg"
								  }).then(
									function () {},
									// handling the promise rejection
									function (dismiss) {
									  if (dismiss === 'timer') {
										window.location.href = 'index.php?p=1';
									  }
									}
								  )
                    
                    //reset values in
					// all input fields
                    $('#register_form input').val('');
                }
//				
				console.log(output);
                $("#result").html(output);
            }, 'json');
		}
        
//		console.log('bitti');
        return false; 
    });
	$("#loginBtn").click(function(){
		var username = $('input[name=username1]').val();
        var password = $('input[name=password1]').val();
        //simple validation at client's end
        //we simply change border color to red if empty field using .css()
        var proceed = true;
        if (username === "") {
            $('input[name=username1]').css('border-color', '#e41919');
            proceed = false;
        }
        if (password == "") {
            $('input[name=password1]').css('border-color', '#e41919');
            proceed = false;
        }
        //everything looks good! proceed...
        if (proceed) {
            //data to be sent to server
			var url='index.php?p=1&a=1';
            var post_data = {
					d :{
						'username': username,
						'password': password
					}
            };
            
            //Ajax post data to server
            $.post(url, post_data, function(response){
				console.log(url);
				console.log(post_data);
                //load json data from server and output message     
                if (response.type == 'error') {
                    output =  swal("Oops!",""+response.text+"","error");
					console.log(response.text);
                }
                else if (response.type == 'success'){
                
                    output = swal({
									title: 'Woow',
									text: 'Giriş Başarılı Anasayfaya gidiyorsunuz ',
									timer: 2000
								  }).then(
									function () {},
									// handling the promise rejection
									function (dismiss) {
									  if (dismiss === 'timer') {
										window.location.href = 'index.php';
									  }
									}
								  )
                    
                    //reset values in
					// all input fields
                    $('#login_form input').val('');
                }
				
				console.log(output);
                $("#result").html(output);
            }, 'json');
            
        }
        
		console.log('asdasdasdasd');
        return false;
    });
    
    //reset previously set border colors and hide all message on .keyup()
    $("#register_form input, #register_form textarea").keyup(function(){
        $("#register_form input, #register_form textarea").css('border-color', '');
        $("#result").slideUp();
    });
    
});