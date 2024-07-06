document.getElementById('feedbackForm').addEventListener('submit', function(e) {
    e.preventDefault();

    var formData = new FormData(this);

    fetch('feedbackForm.php', {
        method: 'POST',
        body: formData,
        credentials: 'include' // Include credentials (cookies) in the request
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Feedback submitted successfully!');
            document.getElementById('feedbackForm').reset(); // Clear the form
        } else {
            alert('Submission failed: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while submitting the form.');
    });
});
