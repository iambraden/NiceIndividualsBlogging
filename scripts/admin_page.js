document.addEventListener('DOMContentLoaded', function(){
    const form = document.querySelector('form');
    
    const userName = document.getElementById('selectUsername');
    const radios = document.getElementsByName("user_role");

    const userReq = userName.insertAdjacentElement('afterend', document.createElement('div'));

    userReq.classList.add('required');

    userReq.innerHTML = 'Username is required';

    userName.innerHTML = 'Select an option';

    let isChecked = false;

    userName.addEventListener('input', function(){
        if(userName.value.trim() === ''){
            userReq.innerHTML = 'Username is required';
        }else{
            userReq.innerHTML = '';
        }
    });

    radios.addEventListener('input', function(){
        for (let i = 0; i < radios.length; i++) {
            if (radios[i].checked) {
                isChecked = true;
                break;
            }
        }
        if (isChecked === false){
            userName.innerHTML = 'Select an option';
        }
    });

    form.addEventListener('submit', function(event){
       
        if(userName.value.trim() === '' || isChecked === false){
            event.preventDefault();
            alert('Please fill in all fields');
        }
        // If all fields are filled, the form will submit to the server
    });
});