document.addEventListener('DOMContentLoaded', function(){
    const form = document.querySelector('form');
    
    const userName = document.getElementById('username');
    const password = document.getElementById('password');

    const userReq = userName.insertAdjacentElement('afterend', document.createElement('div'));
    const passReq = password.insertAdjacentElement('afterend', document.createElement('div'));

    userReq.classList.add('required');
    passReq.classList.add('required');

    userReq.innerHTML = 'Username is required';
    passReq.innerHTML = 'Password is required';

    userName.addEventListener('input', function(){
        if(userName.value.trim() === ''){
            userReq.innerHTML = 'Username is required';
        }else{
            userReq.innerHTML = '';
        }
    });

    password.addEventListener('input', function(){
        if(password.value.trim() === ''){
            passReq.innerHTML = 'Password is required';
        }else{
            passReq.innerHTML = '';
        }
    });

    form.addEventListener('submit', function(event){
       
        if(userName.value.trim() === '' || password.value.trim() === ''){
            event.preventDefault();
            alert('Please fill in all fields');
        }
        // If all fields are filled, the form will submit to the server
    });
});