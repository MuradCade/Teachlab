document.addEventListener("DOMContentLoaded", function () {
  const toggleButton = document.querySelector('[data-mdb-toggle="collapse"]');
  const sidebar = document.getElementById("sidebarMenu");

  // Initialize MDB Collapse instance
  const collapseInstance = new mdb.Collapse(sidebar, { toggle: false });

  toggleButton.addEventListener("click", function (e) {
    e.preventDefault();

    // Toggle sidebar using MDB's collapse methods
    if (sidebar.classList.contains("show")) {
      collapseInstance.hide();
    } else {
      collapseInstance.show();
    }
  });
});
