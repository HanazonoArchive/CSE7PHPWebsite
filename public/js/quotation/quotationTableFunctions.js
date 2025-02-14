document.addEventListener("DOMContentLoaded", () => {
    console.log("Table script loaded");
});

function addRow() {
    const tableBody = document.querySelector("#quotationTable tbody");
    const newRow = tableBody.insertRow();

    newRow.innerHTML = `
        <td><input class="titleContent_InputField" type="text" placeholder="Item"></td>
        <td><input class="titleContent_InputField" type="text" placeholder="Description"></td>
        <td><input class="titleContent_InputField" type="number" oninput="calculateTotal(this)"></td>
        <td><input class="titleContent_InputField" type="number" oninput="calculateTotal(this)"></td>
        <td><span>0.00</span></td>
        <td><button class="submitButton" onclick="deleteRow(this)">Delete</button></td>
    `;
}

function calculateTotal(input) {
    const row = input.closest("tr");
    const quantity = parseFloat(row.cells[2].querySelector("input").value) || 0;
    const price = parseFloat(row.cells[3].querySelector("input").value) || 0;
    const totalCell = row.cells[4].querySelector("span");

    totalCell.innerText = (quantity * price).toFixed(2);
    updateGrandTotal();
}

function updateGrandTotal() {
    const totalCells = document.querySelectorAll("#quotationTable tbody tr td:nth-child(5) span");
    const grandTotal = Array.from(totalCells).reduce((sum, cell) => sum + (parseFloat(cell.innerText) || 0), 0);
    
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