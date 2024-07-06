document.addEventListener('DOMContentLoaded', function() {
    // Fetch user details
    fetch('getUserDetails.php', {
        method: 'GET',
        credentials: 'include'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('userName').textContent = 'Hello, ' + data.userName;
            document.getElementById('userEmail').textContent = 'Email: ' + data.userEmail;
        } else {
            alert('Failed to fetch user details: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while fetching user details.');
    });

    // Show change password form
    document.getElementById('changePasswordBtn').addEventListener('click', function() {
        document.getElementById('changePasswordForm').style.display = 'block';
        this.style.display = 'none';
    });

    // Change password
    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        var newPassword = formData.get('newPassword');
        var confirmNewPassword = formData.get('confirmNewPassword');

        if (newPassword !== confirmNewPassword) {
            alert('New passwords do not match.');
            return;
        }

        fetch('changePassword.php', {
            method: 'POST',
            body: formData,
            credentials: 'include'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Password changed successfully!');
                document.getElementById('changePasswordForm').reset();
                document.getElementById('changePasswordForm').style.display = 'none';
                document.getElementById('changePasswordBtn').style.display = 'block';
            } else {
                alert('Failed to change password: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while changing the password.');
        });
    });

    // Logout
    document.getElementById('logoutBtn').addEventListener('click', function() {
        fetch('logout.php', {
            method: 'POST',
            credentials: 'include'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Logged out successfully!');
                window.location.href = 'index.html';
            } else {
                alert('Failed to log out: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while logging out.');
        });
    });
});
