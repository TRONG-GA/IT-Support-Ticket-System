let ticketCounter = 1;

document.getElementById("ticket-form").addEventListener("submit", function(e) {
  e.preventDefault();
  const shortDesc = document.getElementById("short-description").value;
  const desc = document.getElementById("description").value;
  const priority = document.getElementById("priority").value;
  const createdAt = new Date().toLocaleString(); // Thời gian hiện tại

  const ticket = {
    number: "TCKT-" + String(ticketCounter).padStart(4, "0"),
    shortDescription: shortDesc,
    description: desc,
    priority: priority,
    status: "New",
    assignedTo: "Unassigned",
    createdAt: createdAt // thêm thuộc tính này
  };

  const tickets = JSON.parse(localStorage.getItem("tickets")) || [];
  tickets.push(ticket);
  localStorage.setItem("tickets", JSON.stringify(tickets));

  ticketCounter++;
  displayTickets();
  updateDashboard();
  this.reset();
});

function displayTickets() {
  const tickets = JSON.parse(localStorage.getItem("tickets")) || [];
  const tbody = document.getElementById("ticket-table-body");
  tbody.innerHTML = "";
  tickets.forEach(ticket => {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${ticket.number}</td>
      <td>${ticket.priority}</td>
      <td>${ticket.status}</td>
      <td>${ticket.assignedTo}</td>
      <td>${ticket.createdAt}</td> <!-- hiển thị thời gian -->
    `;
    tbody.appendChild(row);
  });
}

function updateDashboard() {
  const tickets = JSON.parse(localStorage.getItem("tickets")) || [];
  let open = 0, inProgress = 0, resolved = 0;

  tickets.forEach(ticket => {
    const status = ticket.status.toLowerCase();
    if (status === "new" || status === "open") open++;
    else if (status === "in progress") inProgress++;
    else if (status === "resolved") resolved++;
  });

  document.getElementById("open-count").innerText = open;
  document.getElementById("progress-count").innerText = inProgress;
  document.getElementById("resolved-count").innerText = resolved;
}

window.onload = function() {
  displayTickets();
  updateDashboard();
};

