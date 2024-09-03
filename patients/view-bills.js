document.addEventListener('DOMContentLoaded', function() {
    fetch('fetch-bills.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('table tbody');
            tbody.innerHTML = ''; // Clear existing table rows

            if (data.error) {
                tbody.innerHTML = `<tr><td colspan="4">${data.error}</td></tr>`;
                return;
            }

            data.forEach(bill => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${bill.date}</td>
                    <td>${bill.amount}</td>
                    <td>${bill.status}</td>
                    <td>
                        <!-- Add any actions if needed, like view or download -->
                        <button class="btn btn-info btn-sm">View</button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        })
        .catch(error => console.error('Error fetching bills:', error));
});
