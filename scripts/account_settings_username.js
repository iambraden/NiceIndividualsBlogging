document.addEventListener('DOMContentLoaded', function(){
    const form = document.getElementById('usernameForm');
    
    const oldUsername = document.getElementById('oldUsername');
    const newUsername = document.getElementById('newUsername');

    const oldUsernameReq = oldUsername.insertAdjacentElement('afterend', document.createElement('div'));
    const newUsernameReq = newUsername.insertAdjacentElement('afterend', document.createElement('div'));

    oldUsernameReq.classList.add('required');
    newUsernameReq.classList.add('required');

    oldUsernameReq.innerHTML = 'Username is required';
    newUsernameReq.innerHTML = 'Username is required';

    oldUsername.addEventListener('input', function(){
        if(oldUsername.value.trim() === ''){
            oldUsernameReq.innerHTML = 'Username is required';
        }else{
            oldUsernameReq.innerHTML = '';
        }
    }); 

    newUsername.addEventListener('input', function(){
        if(newUsername.value.trim() === ''){
            newUsernameReq.innerHTML = 'Username is required';
        }else{
            newUsernameReq.innerHTML = '';
        }
    });

    form.addEventListener('submit', function(event){
        if(oldUsername.value.trim() === '' || newUsername.value.trim() === ''){
            event.preventDefault();
            alert('Please fill in all fields');
        }
        // If all fields are filled, the form will submit to the server
    });
});