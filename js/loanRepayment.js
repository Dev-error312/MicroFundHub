document.addEventListener('DOMContentLoaded', function() {
    fetchActiveLoans();

    function fetchActiveLoans() {
        fetch('fetchLoans.php', {
            method: 'GET',
            credentials: 'include' // Include credentials (cookies) in the request
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // Parse JSON response
        })
        .then(data => {
            if (data.success) {
                displayLoans(data.loans);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while fetching loans.');
        });
    }

    function displayLoans(loans) {
        const activeLoansDiv = document.getElementById('activeLoans');
        activeLoansDiv.innerHTML = '';

        loans.forEach(loan => {
            const loanDiv = document.createElement('div');
            loanDiv.innerHTML = `
                <p>Loan ID: ${loan.loanId}</p>
                <p>Amount: $${loan.loanAmount}</p>
                <p>Due Date: ${loan.dueDate}</p>
                <hr>
                <label for="amount-${loan.loanId}">Amount:</label>
                <input type="number" id="amount-${loan.loanId}" placeholder="$xxxx">
                <label for="paymentMethod-${loan.loanId}">Payment Method:</label>
                <input type="text" id="paymentMethod-${loan.loanId}" placeholder="Payment Method">
                <button onclick="makePayment(${loan.loanId})" class="registerbtn">Make Payment</button>
            `;
            activeLoansDiv.appendChild(loanDiv);
        });
    }

    window.makePayment = function(loanId) {
        const amount = document.getElementById(`amount-${loanId}`).value;
        const paymentMethod = document.getElementById(`paymentMethod-${loanId}`).value;

        fetch('processRepayment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'loanId': loanId,
                'amount': amount,
                'paymentMethod': paymentMethod
            }),
            credentials: 'include'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                fetchActiveLoans(); // Refresh the list of active loans
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while making the payment.');
        });
    }
});
