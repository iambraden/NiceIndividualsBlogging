document.addEventListener('DOMContentLoaded', function(){
    const form = document.getElementById('passwordForm');
    
    const oldPassword = document.getElementById('oldPassword');
    const newPassword = document.getElementById('newPassword');

    const oldPasswordReq = oldPassword.insertAdjacentElement('afterend', document.createElement('div'));
    const newPasswordReq = newPassword.insertAdjacentElement('afterend', document.createElement('div'));

    oldPasswordReq.classList.add('required');
    newPasswordReq.classList.add('required');

    oldPasswordReq.innerHTML = 'Password is required';
    newPasswordReq.innerHTML = 'Password is required';

    oldPassword.addEventListener('input', function(){
        if(oldPassword.value.trim() === ''){
            oldPasswordReq.innerHTML = 'Password is required';
        }else{
            oldPasswordReq.innerHTML = '';
        }
    }); 

    newPassword.addEventListener('input', function(){
        if(newPassword.value.trim() === ''){
            newPasswordReq.innerHTML = 'Password is required';
        }else{
            newPasswordReq.innerHTML = '';
        }
    });

    form.addEventListener('submit', function(event){
        if(oldPassword.value.trim() === '' || newPassword.value.trim() === ''){
            event.preventDefault();
            alert('Please fill in all fields');
        }
        // If all fields are filled, the form will submit to the server
    });
});