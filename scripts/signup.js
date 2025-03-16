document.addEventListener('DOMContentLoaded', function(){
    const form = document.querySelector('form');
    
    const firstName = document.getElementById('firstname');
    const lastName = document.getElementById('lastname');
    const email = document.getElementById('email');
    const userName = document.getElementById('username');
    const password = document.getElementById('password');

    const firstNameReq = firstName.insertAdjacentElement('afterend', document.createElement('div'));
    const lastNameReq = lastName.insertAdjacentElement('afterend', document.createElement('div'));
    const emailReq = email.insertAdjacentElement('afterend', document.createElement('div'));
    const userReq = userName.insertAdjacentElement('afterend', document.createElement('div'));
    const passReq = password.insertAdjacentElement('afterend', document.createElement('div'));

    firstNameReq.classList.add('required');
    lastNameReq.classList.add('required');
    emailReq.classList.add('required');
    userReq.classList.add('required');
    passReq.classList.add('required');

    firstNameReq.innerHTML = 'First Name is required';
    lastNameReq.innerHTML = 'Last Name is required';
    emailReq.innerHTML = 'Email is required';
    userReq.innerHTML = 'Username is required';
    passReq.innerHTML = 'Password is required';

    firstName.addEventListener('input', function(){
        if(firstName.value.trim() === ''){
            firstNameReq.innerHTML = 'First Name is required';
        }else{
            firstNameReq.innerHTML = '';
        }
    });

    lastName.addEventListener('input', function(){
        if(lastName.value.trim() === ''){
            lastNameReq.innerHTML = 'Last Name is required';
        }else{
            lastNameReq.innerHTML = '';
        }
    }); 

    email.addEventListener('input', function(){
        if(email.value.trim() === ''){
            emailReq.innerHTML = 'Email is required';
        }else{
            emailReq.innerHTML = '';
        }
    }); 

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
        event.preventDefault();

        if(firstName.value.trim() === '' || lastName.value.trim() === '' || email.value.trim() === '' || userName.value.trim() === '' || password.value.trim() === ''){
            alert('Please fill in all fields');
        }else{
            alert('Form submitted successfully');
        }
    });
});