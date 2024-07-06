document.getElementById('registrationForm').addEventListener('submit', function(e) {
    e.preventDefault();

    var formData = new FormData(this);
    var password = formData.get('psw');
    var confirmPassword = formData.get('psw-confirm');

    if (password !== confirmPassword) {
        alert('Passwords do not match.');
        return;
    }

    fetch('RegistrationForm.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Registration successful!');
        } else {
            alert('Registration failed: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while submitting the form.');
    });
});