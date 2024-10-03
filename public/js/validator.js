document.querySelector('form').addEventListener('submit', function(event) {
  const questionTextarea = document.getElementById('question');
  const affirmationTextarea = document.getElementById('affirmation');
  const justificationTextarea = document.getElementById('justification');
  const evidenceTextarea = document.getElementById('evidence');
  const correctOption = document.getElementById('correctOption');
  const option2 = document.getElementById('option2');
  const option3 = document.getElementById('option3');
  const option4 = document.getElementById('option4');

  // Inicializar un array para almacenar los mensajes de error
  let errors = [];

  // Validar que la pregunta no esté vacía
  if (!questionTextarea.value.trim()) {
    errors.push('Por favor, ingrese una pregunta.');
    questionTextarea.focus();
  }

  // Validar los campos adicionales solo si están visibles
  const additionalFields = document.getElementById('additionalFields');
  if (additionalFields.style.display !== 'none') {
    // Validar correctOption
    if (!correctOption.value.trim()) {
      errors.push('Por favor, ingrese una opción correcta válida.');
      correctOption.focus();
    }
  
    // Validar que las demás opciones (option2, option3, option4) no solo tengan espacios si están llenas
    if (option2.value && !option2.value.trim()) {
      errors.push('La Opción 2 no debe contener solo espacios.');
    }
  
    if (option3.value && !option3.value.trim()) {
      errors.push('La Opción 3 no debe contener solo espacios.');
    }
  
    if (option4.value && !option4.value.trim()) {
      errors.push('La Opción 4 no debe contener solo espacios.');
    }
    // Validar afirmación
    if (!affirmationTextarea.value.trim()) {
      errors.push('Por favor, ingrese una afirmación.');
    }

    // Validar justificación
    if (!justificationTextarea.value.trim()) {
      errors.push('Por favor, ingrese una justificación.');
    }

    // Validar evidencia
    if (!evidenceTextarea.value.trim()) {
      errors.push('Por favor, ingrese una evidencia.');
    }
  }

  // Si hay errores, prevenir el envío y mostrar los mensajes
  if (errors.length > 0) {
    event.preventDefault();
    alert(errors.join('\n')); // Mostrar todos los errores en un solo alert
  }
});
