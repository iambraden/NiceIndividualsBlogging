document.addEventListener('DOMContentLoaded', function(){
    const form = document.querySelector('form');
    
    const oldEmail = document.getElementById('oldEmail');
    const newEmail = document.getElementById('newEmail');

    const oldEmailReq = oldEmail.insertAdjacentElement('afterend', document.createElement('div'));
    const newEmailReq = newEmail.insertAdjacentElement('afterend', document.createElement('div'));

    oldEmailReq.classList.add('required');
    newEmailReq.classList.add('required');

    oldEmailReq.innerHTML = 'Email is required';
    newEmailReq.innerHTML = 'Email is required';

    oldEmail.addEventListener('input', function(){
        if(oldEmail.value.trim() === ''){
            oldEmailReq.innerHTML = 'Email is required';
        }else{
            oldEmailReq.innerHTML = '';
        }
    }); 

    newEmail.addEventListener('input', function(){
        if(newEmail.value.trim() === ''){
            newEmailReq.innerHTML = 'Email is required';
        }else{
            newEmailReq.innerHTML = '';
        }
    });

    form.addEventListener('submit', function(event){
        if(oldEmail.value.trim() === '' || newEmail.value.trim() === ''){
            event.preventDefault();
            alert('Please fill in all fields');
        }
        // If all fields are filled, the form will submit to the server
    });
});