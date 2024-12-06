document.getElementById('donation-amount').addEventListener('input', updateAmounts);
        document.getElementById('tip-amount').addEventListener('input', updateAmounts);

        function updateAmounts() {
            const donationAmount = parseFloat(document.getElementById('donation-amount').value) || 0;
            const tipAmount = parseFloat(document.getElementById('tip-amount').value) || 0;
            const totalAmount = donationAmount + tipAmount;

            document.getElementById('donation-display').innerText = 'NPR ' + donationAmount.toFixed(2);
            document.getElementById('tip-display').innerText = 'NPR ' + tipAmount.toFixed(2);
            document.getElementById('total-display').innerText = 'NPR ' + totalAmount.toFixed(2);
        }
