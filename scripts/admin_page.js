document.addEventListener('DOMContentLoaded', function(){
    const form = document.querySelector('form');
    
    const userName = document.getElementById('selectUsername');
    const userByEmail = document.getElementById('selectUserByEmail');
    const radios = document.getElementsByName("user_role");

    const userReq = userName.insertAdjacentElement('afterend', document.createElement('div'));

    userReq.classList.add('required');

    userName.innerHTML = 'Select an option';
    
    let isChecked = false;
    let isFilled = false;

    userName.addEventListener('input', function(){
        if(userName.value.trim() === '' && userByEmail.value.trim() === ''){
            userReq.innerHTML = 'A username or user\'s email is required';
            isFilled = false;
        }else{
            userReq.innerHTML = '';
            isFilled = true;
        }
    });

    radios.forEach(function (radio) {
        radio.addEventListener('input', function () {
            isChecked = Array.from(radios).some(radio => radio.checked);
        });
    });

    form.addEventListener('submit', function(event){
       
        if(isFilled === false || isChecked === false){
            event.preventDefault();
            alert('Please fill in required fields');
        }
        // If all fields are filled, the form will submit to the server
    });
});