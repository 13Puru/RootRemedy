document.addEventListener("DOMContentLoaded", function () {
  // Navigation Links
  const navLinks = {
    deleteLink: "delete.php",
    med: "addMed.php",
    dis: "AddDis.php",
    pnt: "addPnt.php",
    con: "consultancy.php",
    report: "report.php",
    fea: "featured.php",
    pri: "privacy.html",
    use: "user_manual.html",
    cpass: "changePass.php",
    log: "activity_log.php"  // Activity log URL
  };

  // Attach click event to navigation links
  Object.keys(navLinks).forEach((linkId) => {
    const linkElement = document.getElementById(linkId);
    if (linkElement) {
      linkElement.addEventListener("click", function (e) {
        e.preventDefault();
        loadContent(navLinks[linkId]);
      });
    }
  });

  // Function to load content dynamically into containerArea
  function loadContent(url) {
    console.log(`Loading content from URL: ${url}`);
    var xhr = new XMLHttpRequest();
    xhr.open("GET", url, true);
    xhr.onload = function () {
      if (xhr.status === 200) {
        console.log("Content loaded successfully.");
        document.getElementById("containerArea").innerHTML = xhr.responseText;

        // Re-attach form submission and delete handlers after loading new content
        attachFormSubmitHandler(url);
        attachDeleteOptionHandler();
        attachDeleteButtonHandlers();
      } else {
        console.error("Error loading content:", xhr.statusText);
      }
    };
    xhr.onerror = function () {
      console.error("Request failed");
    };
    xhr.send();
  }

  // Function to generate report
  function generateReport() {
    console.log("Generating report...");
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "generateReport.php", true); // Adjust URL as needed for report generation
    xhr.onload = function () {
      if (xhr.status === 200) {
        console.log("Report generated successfully.");
        alert("Report generated! Check your downloads.");
      } else {
        console.error("Error generating report:", xhr.statusText);
      }
    };
    xhr.onerror = function () {
      console.error("Request failed while generating report");
    };
    xhr.send();
  }

  // Handle form submissions using AJAX
  function attachFormSubmitHandler(actionUrl) {
    var form = document.querySelector("#containerArea form");
    if (form) {
      console.log(`Form found. Attaching submit handler for action: ${actionUrl}`);
      form.addEventListener("submit", function (e) {
        e.preventDefault();

        var xhr = new XMLHttpRequest();
        xhr.open("POST", actionUrl, true);
        xhr.onload = function () {
          if (xhr.status === 200) {
            console.log("Form submitted successfully.");
            document.getElementById("containerArea").innerHTML = xhr.responseText;
            attachFormSubmitHandler(actionUrl);
            attachDeleteOptionHandler();
            attachDeleteButtonHandlers();
          } else {
            console.error("Error submitting form:", xhr.statusText);
          }
        };
        xhr.onerror = function () {
          console.error("Request failed while submitting form");
        };
        var formData = new FormData(form);
        xhr.send(formData);
      });
    } else {
      console.warn("No form found in containerArea");
    }
  }

  // Handle delete option change
  function attachDeleteOptionHandler() {
    const deleteOption = document.getElementById("deleteOption");
    if (deleteOption) {
      deleteOption.addEventListener("change", function (e) {
        e.preventDefault();
        loadDeleteContent(deleteOption.value);
      });
    }
  }

  // Load respective delete content (plants, diseases, medicines)
  function loadDeleteContent(option) {
    var xhr = new XMLHttpRequest();
    var url = "";

    switch (option) {
      case "plants":
        url = "fetchDeletePlants.php";
        break;
      case "disease":
        url = "fetchDeleteDiseases.php";
        break;
      case "medicine":
        url = "fetchDeleteMedicines.php";
        break;
      default:
        console.error("Invalid delete option");
        return;
    }

    xhr.open("GET", url, true);
    xhr.onload = function () {
      if (xhr.status === 200) {
        document.getElementById("containerArea").innerHTML = xhr.responseText;
        attachDeleteButtonHandlers();
      } else {
        console.error("Error loading delete content:", xhr.statusText);
      }
    };
    xhr.onerror = function () {
      console.error("Request failed while loading delete content");
    };
    xhr.send();
  }

  // Handle delete button clicks
  function attachDeleteButtonHandlers() {
    const deleteButtons = document.querySelectorAll(".delete-btn");
    deleteButtons.forEach((button) => {
      button.addEventListener("click", function (e) {
        e.preventDefault();
        const id = button.getAttribute("data-id");
        const type = button.getAttribute("data-type");
        const row = button.closest("tr");

        if (confirm(`Are you sure you want to delete this ${type}?`)) {
          deleteItem(id, type, row);
        }
      });
    });
  }

  // Separate delete action with AJAX
  function deleteItem(id, type, row) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "deleteItem.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
      if (xhr.status === 200) {
        alert(xhr.responseText);
        if (row) {
          row.remove();
        }
      } else {
        console.error("Error deleting item:", xhr.statusText);
      }
    };
    xhr.onerror = function () {
      console.error("Request failed while deleting item");
    };
    xhr.send(`id=${id}&type=${type}`);
  }

  // Search functionality
  const searchButton = document.getElementById("searchButton");
  const searchInput = document.getElementById("searchInput");

  if (searchButton) {
    searchButton.addEventListener("click", function () {
      const query = searchInput.value.trim();
      if (query) {
        performSearch(query);
      } else {
        alert("Please enter a search term.");
      }
    });
  }

  // Function to perform the search
  function performSearch(query) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "search.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
      if (xhr.status === 200) {
        document.getElementById("containerArea").innerHTML = xhr.responseText;
      } else {
        console.error("Error during search:", xhr.statusText);
      }
    };
    xhr.onerror = function () {
      console.error("Request failed while searching");
    };
    xhr.send(`query=${encodeURIComponent(query)}`);
  }
});
