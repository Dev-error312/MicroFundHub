document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();

    var formData = new FormData(this);

    fetch('LoginForm.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())  // Get response as text to debug potential HTML errors
    .then(text => {
        try {
            const data = JSON.parse(text);  // Try to parse JSON
            if (data.success) {
                alert('Login successful!');
                window.location.href = 'home.html';
            } else {
                alert('Login failed: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            console.error('Response Text:', text);
            alert('An error occurred while logging in.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while logging in.');
    });
});
