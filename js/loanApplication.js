document.getElementById('loanApplicationForm').addEventListener('submit', function(e) {
    e.preventDefault();

    var formData = new FormData(this);

    fetch('LoanApplicationForm.php', {
        method: 'POST',
        body: formData,
        credentials: 'include' // Include credentials (cookies) in the request
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Loan application submitted successfully!');
        } else {
            alert('Submission failed: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while submitting the form.');
    });
});
