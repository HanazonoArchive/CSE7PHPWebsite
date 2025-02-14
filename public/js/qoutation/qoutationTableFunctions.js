document.addEventListener("DOMContentLoaded", () => {
    console.log("Table script loaded");
    
    const generateQuotationButton = document.getElementById("generateQoutation");
    if (generateQuotationButton) {
        generateQuotationButton.addEventListener("click", sendGrandTotal);
    }
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

function sendGrandTotal() {
    const grandTotalElement = document.getElementById("grandTotalInput");
    if (!grandTotalElement) {
        console.error("Error: Grand total display element not found.");
        return;
    }

    const grandTotal = parseFloat(grandTotalElement.innerText);
    const appointmentID = document.getElementById("qoutationDetails_AppointmentID")?.value.trim();
    const employeeID1 = document.getElementById("qoutationDetails_EmployeeID1")?.value.trim();
    const employeeID2 = document.getElementById("qoutationDetails_EmployeeID2")?.value.trim();
    const employeeID3 = document.getElementById("qoutationDetails_EmployeeID3")?.value.trim();

    // Validation checks
    if (!appointmentID) {
        updateQueryStatus("Error: Appointment ID is required.", "red", "pink");
        return;
    }

    const hasAtLeastOneEmployee = employeeID1 || employeeID2 || employeeID3;
    if (!hasAtLeastOneEmployee) {
        updateQueryStatus("Error: At least one employee ID is required.", "red", "pink");
        return;
    }

    if (grandTotal <= 0) {
        updateQueryStatus("Error: Grand total must be greater than 0.00.", "red", "pink");
        return;
    }

    // If all conditions are met, proceed with sending data
    const formData = new URLSearchParams({
        grandTotal: grandTotal.toFixed(2),
        employeeID1: employeeID1 || "",
        employeeID2: employeeID2 || "",
        employeeID3: employeeID3 || "",
        appointmentID,
        appointmentStatus: "Working"
    });

    console.log("Sending data:", formData.toString());

    fetch("quotation.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: formData.toString(),
    })
    .then(response => response.text())
    .then(data => updateQueryStatus("Quotation submitted successfully!", "green", "lightgreen"))
    .catch(error => updateQueryStatus("Error submitting quotation.", "red", "pink"));
}

function updateQueryStatus(message, textColor, bgColor) {
    const statusNotifier = document.getElementById("statusGenerateNotifier");
    if (statusNotifier) {
        Object.assign(statusNotifier.style, {
            color: textColor,
            backgroundColor: bgColor,
            border: `1px solid ${textColor}`
        });
        statusNotifier.textContent = message;
    }
}

function deleteRow(button) {
    button.closest("tr").remove();
    updateGrandTotal();
}
