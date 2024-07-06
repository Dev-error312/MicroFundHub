document.addEventListener('DOMContentLoaded', function() {
    fetchLoans();
});

function fetchLoans() {
    fetch('loanProgress.php', {
        method: 'GET',
        credentials: 'include' // Include credentials (cookies) in the request
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayLoans(data.loans);
        } else {
            console.error('Error:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function displayLoans(loans) {
    const tbody = document.querySelector('#loanTable tbody');
    tbody.innerHTML = ''; // Clear existing content

    loans.forEach(loan => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${loan.loanAmount}</td>
            <td>${loan.purpose}</td>
            <td>${loan.status}</td>
            <td>${loan.date}</td>
        `;
        tbody.appendChild(row);
    });
}
