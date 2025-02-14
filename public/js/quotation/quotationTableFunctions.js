document.addEventListener("DOMContentLoaded", () => {
    console.log("Table script loaded");
    document.getElementById("generateQoutation").addEventListener("click", async function () {
        console.log("Generating quotation...");
        sendData();
    });
});

function addRow() {
    const tableBody = document.querySelector("#quotationTable tbody");
    const newRow = tableBody.insertRow();

    newRow.innerHTML = `
        <td><input class="titleContent_InputField" type="text" name="item[]" placeholder="Item"></td>
        <td><input class="titleContent_InputField" type="text" name="description[]" placeholder="Description"></td>
        <td><input class="titleContent_InputField" type="number" name="quantity[]" oninput="calculateTotal(this)"></td>
        <td><input class="titleContent_InputField" type="number" name="price[]" oninput="calculateTotal(this)"></td>
        <td><span>0.00</span></td>
        <td><button class="submitButton" onclick="deleteRow(this)">Delete</button></td>
    `;
}

function calculateTotal(input) {
    const row = input.closest("tr");
    const quantity = Number(row.cells[2].querySelector("input").value) || 0;
    const price = Number(row.cells[3].querySelector("input").value) || 0;
    const totalCell = row.cells[4].querySelector("span");

    totalCell.innerText = (quantity * price).toFixed(2);
    updateGrandTotal();
}

function updateGrandTotal() {
    const totalCells = document.querySelectorAll("#quotationTable tbody tr td:nth-child(5) span");
    const grandTotal = Array.from(totalCells).reduce((sum, cell) => sum + (Number(cell.innerText) || 0), 0);
    
    const grandTotalDisplay = document.getElementById("grandTotalInput");
    if (grandTotalDisplay) {
        grandTotalDisplay.innerText = grandTotal.toFixed(2);
    } else {
        console.error("Error: 'grandTotalInput' element not found.");
    }
}

function deleteRow(button) {
    button.closest("tr").remove();
    updateGrandTotal();
}

function sendData() {
    const tableRows = document.querySelectorAll("#quotationTable tbody tr");
    const formData = new FormData();
    let hasError = false;

    tableRows.forEach((row, index) => {
        const item = row.cells[0].querySelector("input").value.trim();
        const description = row.cells[1].querySelector("input").value.trim();
        const quantity = row.cells[2].querySelector("input").value.trim();
        const price = row.cells[3].querySelector("input").value.trim();
        const total = row.cells[4].querySelector("span").innerText.trim();

        if (!item || !description || !quantity || !price) {
            hasError = true;
            console.error(`Row ${index + 1}: Missing required fields.`);
            return;
        }

        formData.append(`items[${index}][item]`, item);
        formData.append(`items[${index}][description]`, description);
        formData.append(`items[${index}][quantity]`, quantity);
        formData.append(`items[${index}][price]`, price);
        formData.append(`items[${index}][total]`, total);
    });

    if (hasError) {
        alert("Please fill in all required fields before generating the quotation.");
        return;
    }

    formData.append("action", "insertQuotationItems");

    fetch("quotation.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.text())
    .then(data => console.log("Success:", data))
}

