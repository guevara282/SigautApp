function toggleFields() {
    var courseType = document.getElementById("courseType").value;
    var additionalFields = document.getElementById("additionalFields");
    var fields = additionalFields.querySelectorAll("input, textarea");

    function setRequiredAttributes(element, required) {
      element.forEach(function(field) {
        if (required) {
          field.setAttribute("required", "required");
          field.removeAttribute("disabled");
        } else {
          field.removeAttribute("required");
          field.setAttribute("disabled", "disabled");
        }
      });
    }

    if (courseType === "Seleccion multiple") {
      additionalFields.style.display = "block";
      setRequiredAttributes(fields, true);
    } else if (courseType === "Abierta") {
      additionalFields.style.display = "none";
      setRequiredAttributes(fields, false);
    } else {
      additionalFields.style.display = "none";
      setRequiredAttributes(fields, false);
    }
  }

  // El campo "question" estará siempre habilitado y requerido
  window.onload = function() {
    var questionField = document.getElementById("question");
    questionField.setAttribute("required", "required");
    questionField.removeAttribute("disabled");
  };