<?php
require_once('D:\BD\laragon-6.0.0\www\dist\config.php');

require_login(); // Verifica que el usuario esté autenticado
global $USER, $DB;

// Verificar si el usuario tiene el rol adecuado
$context = context_system::instance(); // Contexto global

// Verificar roles en el contexto global (gestor global y administrador del sitio)
$sql_global = "SELECT r.shortname
      FROM {role_assignments} ra
      JOIN {context} c ON c.id = ra.contextid
      JOIN {role} r ON r.id = ra.roleid
      WHERE ra.userid = :userid AND c.contextlevel = :contextlevel";
$params_global = [
  'userid' => $USER->id,
  'contextlevel' => CONTEXT_SYSTEM
];
$global_roles = $DB->get_records_sql($sql_global, $params_global);

// Verificar roles en el contexto de curso (docente, editor, gestor de curso)
$sql_course = "SELECT r.shortname
      FROM {role_assignments} ra
      JOIN {context} c ON c.id = ra.contextid
      JOIN {role} r ON r.id = ra.roleid
      WHERE ra.userid = :userid AND c.contextlevel = :contextlevel";
$params_course = [
  'userid' => $USER->id,
  'contextlevel' => CONTEXT_COURSE
];
$course_roles = $DB->get_records_sql($sql_course, $params_course);

$has_role = false;

// Verificar si tiene rol de gestor global o administrador
foreach ($global_roles as $role) {
  if ($role->shortname == 'manager' || is_siteadmin()) {
    $has_role = true;
    break;
  }
}

// Verificar si tiene rol de docente, editor o gestor en un curso
if (!$has_role) { // Solo si no es gestor global o admin, verificar los roles de curso
  foreach ($course_roles as $role) {
    if ($role->shortname == 'editingteacher' || $role->shortname == 'teacher' || $role->shortname == 'manager') {
      $has_role = true;
      break;
    }
  }
}

// Verificar si el usuario tiene el rol adecuado
if ($has_role || is_siteadmin()) {
  $usuario = $USER->username;  // Obtener el nombre de usuario de Moodle
  $userid = $USER->id;         // Obtener el ID del usuario de Moodle
} else {
  // Redirigir al inicio de sesión de Moodle si no está autenticado
  header('Location: ' . $CFG->wwwroot . '/login/index.php');
  exit();
}
?>
