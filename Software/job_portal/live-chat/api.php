<?php
// api.php
header('Content-Type: application/json; charset=utf-8');
session_start();
require_once "databaseconc.php";


if (!isset($_SESSION["sid"])) {
  $_SESSION["sid"] = bin2hex(random_bytes(16));
}
$sid = $_SESSION["sid"];


if (!isset($_SESSION["state"])) $_SESSION["state"] = "START";
if (!isset($_SESSION["ctx"])) $_SESSION["ctx"] = [
  "topic" => "General",
  "last_solution" => "",
];

$input = json_decode(file_get_contents("php://input"), true) ?? [];
$action = $input["action"] ?? "message"; 
$payload = $input["payload"] ?? "";      
$text = trim($input["text"] ?? "");       

function log_msg($conn, $sid, $sender, $msg) {
  $stmt = mysqli_prepare($conn, "INSERT INTO chat_logs(session_id, sender, message) VALUES(?,?,?)");
  mysqli_stmt_bind_param($stmt, "sss", $sid, $sender, $msg);
  mysqli_stmt_execute($stmt);
}

function respond($message, $options = [], $stateOverride = null) {
  $res = [
    "ok" => true,
    "message" => $message,
    "options" => $options,
    "state" => $stateOverride ?? $_SESSION["state"],
    "ctx" => $_SESSION["ctx"],
    "sid" => $_SESSION["sid"]
  ];
  echo json_encode($res);
  exit;
}

function btn($label, $value) { return ["label"=>$label, "value"=>$value]; }


if ($action === "reset") {
  $_SESSION["state"] = "START";
  $_SESSION["ctx"] = ["topic"=>"General", "last_solution"=>""];
  respond("Restarted ✅. Need help?", [btn("Start", "START")], "START");
}


if ($payload !== "") log_msg($conn, $sid, "USER", "[button] ".$payload);
if ($text !== "") log_msg($conn, $sid, "USER", $text);

$state = $_SESSION["state"];
$ctx = &$_SESSION["ctx"];


function solved_branch($solvedYesValue = "SOLVED_YES", $solvedNoValue = "SOLVED_NO") {
  return [
    btn("Yes", $solvedYesValue),
    btn("No", $solvedNoValue)
  ];
}


switch ($state) {

  case "START":
    $_SESSION["state"] = "ASK_LOGGED_IN";
    $msg = "Hi! Start: Need help?\nAre you logged in?";
    log_msg($conn, $sid, "BOT", $msg);
    respond($msg, [btn("Yes", "LOGGED_IN_YES"), btn("No", "LOGGED_IN_NO")]);

  case "ASK_LOGGED_IN":
    if ($payload === "LOGGED_IN_NO") {
      $ctx["topic"] = "Login";
      $_SESSION["state"] = "SOLN_LOGIN";
      $msg = "Suggest: Login / Reset Password.\n\nTry:\n1) Click “Forgot Password”\n2) Check Spam/Junk\n3) Make sure Caps Lock is off\n\nDid this solve it?";
      log_msg($conn, $sid, "BOT", $msg);
      respond($msg, solved_branch());
    }
    if ($payload === "LOGGED_IN_YES") {
      $_SESSION["state"] = "ASK_TOPIC";
      $msg = "What do you need help with?";
      log_msg($conn, $sid, "BOT", $msg);
      respond($msg, [
        btn("Account issue", "TOPIC_ACCOUNT"),
        btn("Applying for a job", "TOPIC_APPLY"),
        btn("CV / Upload", "TOPIC_CV"),
        btn("Employer / Posting", "TOPIC_EMPLOYER"),
        btn("Payments / Subscription", "TOPIC_PAYMENTS"),
        btn("Other / Not listed", "TOPIC_OTHER"),
      ]);
    }
    
    $msg = "Please choose Yes/No using the buttons.";
    log_msg($conn, $sid, "BOT", $msg);
    respond($msg, [btn("Yes", "LOGGED_IN_YES"), btn("No", "LOGGED_IN_NO")]);

  case "ASK_TOPIC":
    if ($payload === "TOPIC_ACCOUNT") {
      $ctx["topic"] = "Account";
      $_SESSION["state"] = "SOLN_ACCOUNT";
      $msg = "Account issue \n\nOptions:\n• Update profile\n• Change email\n• Delete account\n\nDid this solve it?";
      log_msg($conn, $sid, "BOT", $msg);
      respond($msg, solved_branch());
    }

    if ($payload === "TOPIC_APPLY") {
      $ctx["topic"] = "Apply";
      $_SESSION["state"] = "APPLY_ERROR";
      $msg = "Applying for a job \n\nIs the 'Apply' button missing / giving an error?";
      log_msg($conn, $sid, "BOT", $msg);
      respond($msg, [btn("Yes", "APPLY_ERR_YES"), btn("No", "APPLY_ERR_NO")]);
    }

    if ($payload === "TOPIC_CV") {
      $ctx["topic"] = "CV Upload";
      $_SESSION["state"] = "SOLN_CV";
      $msg = "CV / Upload issue \n\nCheck:\n• File type (PDF/DOCX)\n• File size (keep small)\n• Rename file (no weird symbols)\n• Try another browser\n\nDid this solve it?";
      log_msg($conn, $sid, "BOT", $msg);
      respond($msg, solved_branch());
    }

    if ($payload === "TOPIC_EMPLOYER") {
      $ctx["topic"] = "Employer Posting";
      $_SESSION["state"] = "SOLN_EMPLOYER";
      $msg = "Employer / posting issue ✅\n\nHelp:\n• Create posting\n• Edit posting\n• View applicants\n• Confirm account approved\n\nDid this solve it?";
      log_msg($conn, $sid, "BOT", $msg);
      respond($msg, solved_branch());
    }

    if ($payload === "TOPIC_PAYMENTS") {
      $ctx["topic"] = "Payments";
      $_SESSION["state"] = "SOLN_PAYMENTS";
      $msg = "Payments / subscription \n\nHelp:\n• Billing & invoices\n• Update payment method\n• Cancel plan\n• Check for pending invoice\n\nDid this solve it?";
      log_msg($conn, $sid, "BOT", $msg);
      respond($msg, solved_branch());
    }

    if ($payload === "TOPIC_OTHER") {
      $ctx["topic"] = "Other";
      $_SESSION["state"] = "ESCALATE_PHONE";
      $msg = "Other / not listed.\nOffer: Call back / Phone support.\n\nWant a phone call?";
      log_msg($conn, $sid, "BOT", $msg);
      respond($msg, [btn("Yes", "CALL_YES"), btn("No", "CALL_NO")]);
    }

    $msg = "Pick a category using the buttons so I can route you correctly.";
    log_msg($conn, $sid, "BOT", $msg);
    respond($msg, [
      btn("Account issue", "TOPIC_ACCOUNT"),
      btn("Applying for a job", "TOPIC_APPLY"),
      btn("CV / Upload", "TOPIC_CV"),
      btn("Employer / Posting", "TOPIC_EMPLOYER"),
      btn("Payments / Subscription", "TOPIC_PAYMENTS"),
      btn("Other / Not listed", "TOPIC_OTHER"),
    ]);

  case "APPLY_ERROR":
    if ($payload === "APPLY_ERR_YES") {
      $_SESSION["state"] = "SOLN_APPLY_ERR";
      $msg = "Check: CV uploaded + profile complete + browser steps.\n\nTry:\n1) Upload your CV\n2) Complete profile (contact info, education)\n3) Disable adblock for this site\n4) Refresh & try Chrome/Edge\n\nSolved?";
      log_msg($conn, $sid, "BOT", $msg);
      respond($msg, solved_branch());
    }
    if ($payload === "APPLY_ERR_NO") {
      $_SESSION["state"] = "SOLN_APPLY_TRACK";
      $msg = "Help: Track application status / confirm submission.\n\nGo to:\nProfile → My Applications → select job → view status.\n\nSolved?";
      log_msg($conn, $sid, "BOT", $msg);
      respond($msg, solved_branch());
    }
    $msg = "Use the buttons: Yes (error) or No (no error).";
    log_msg($conn, $sid, "BOT", $msg);
    respond($msg, [btn("Yes", "APPLY_ERR_YES"), btn("No", "APPLY_ERR_NO")]);


  case "SOLN_LOGIN":
  case "SOLN_ACCOUNT":
  case "SOLN_CV":
  case "SOLN_EMPLOYER":
  case "SOLN_PAYMENTS":
  case "SOLN_APPLY_ERR":
  case "SOLN_APPLY_TRACK":

    if ($payload === "SOLVED_YES") {
      $_SESSION["state"] = "END";
      $msg = "End: Glad I could help! \n\nNeed anything else?";
      log_msg($conn, $sid, "BOT", $msg);
      respond($msg, [btn("Start over", "RESTART"), btn("No thanks", "DONE")]);
    }

    if ($payload === "SOLVED_NO") {
      $_SESSION["state"] = "ESCALATE_PHONE";
      $msg = "Sorry it didn’t solve it.\nOffer: Call back / Phone support.\n\nWant a phone call?";
      log_msg($conn, $sid, "BOT", $msg);
      respond($msg, [btn("Yes", "CALL_YES"), btn("No", "CALL_NO")]);
    }

    $msg = "Please answer using the Yes/No buttons.";
    log_msg($conn, $sid, "BOT", $msg);
    respond($msg, solved_branch());

  case "ESCALATE_PHONE":
    if ($payload === "CALL_NO") {
      $_SESSION["state"] = "END";
      $msg = "No problem. Offer: email / reopen chat.\n\nWant to start over?";
      log_msg($conn, $sid, "BOT", $msg);
      respond($msg, [btn("Start over", "RESTART"), btn("Close chat", "DONE")]);
    }

    if ($payload === "CALL_YES") {
      $_SESSION["state"] = "COLLECT_CALL";
      $msg = "Collect: name + phone + best time.\n\nPlease fill the form below.";
      log_msg($conn, $sid, "BOT", $msg);
      respond($msg, [
        ["label"=>"Open call form", "value"=>"OPEN_CALL_FORM", "type"=>"form"]
      ]);
    }

    $msg = "Want a phone call? (Yes/No)";
    log_msg($conn, $sid, "BOT", $msg);
    respond($msg, [btn("Yes", "CALL_YES"), btn("No", "CALL_NO")]);

  case "COLLECT_CALL":

    if ($action === "create_ticket") {
      $name = trim($input["name"] ?? "");
      $phone = trim($input["phone"] ?? "");
      $best_time = trim($input["best_time"] ?? "");
      $topic = $ctx["topic"] ?? "General";

      if ($name === "" || $phone === "" || $best_time === "") {
        respond("Please fill in name, phone, and best time.", [
          ["label"=>"Open call form", "value"=>"OPEN_CALL_FORM", "type"=>"form"]
        ]);
      }

      $summary = "User requested phone support.";
      $stmt = mysqli_prepare($conn, "INSERT INTO support_tickets(session_id,name,phone,best_time,topic,summary) VALUES(?,?,?,?,?,?)");
      mysqli_stmt_bind_param($stmt, "ssssss", $sid, $name, $phone, $best_time, $topic, $summary);
      mysqli_stmt_execute($stmt);
      $ticketId = mysqli_insert_id($conn);

      $_SESSION["state"] = "END";
      $msg = "Create support ticket + show confirmation \nTicket #$ticketId\n\nWe’ll call you at $phone (best time: $best_time).";
      log_msg($conn, $sid, "BOT", $msg);
      respond($msg, [btn("Start over", "RESTART"), btn("Close chat", "DONE")], "END");
    }

    
    if ($payload === "OPEN_CALL_FORM") {
      respond("Please use the call form to submit your details.", [
        ["label"=>"Open call form", "value"=>"OPEN_CALL_FORM", "type"=>"form"]
      ]);
    }

    $msg = "Please use the call form to submit your details.";
    log_msg($conn, $sid, "BOT", $msg);
    respond($msg, [
      ["label"=>"Open call form", "value"=>"OPEN_CALL_FORM", "type"=>"form"]
    ]);

  case "END":
    if ($payload === "RESTART") {
      $_SESSION["state"] = "START";
      $ctx["topic"] = "General";
      $ctx["last_solution"] = "";
      $msg = "Restarting…";
      log_msg($conn, $sid, "BOT", $msg);
      respond($msg, [btn("Start", "START")], "START");
    }
    if ($payload === "DONE") {
      $msg = "Chat closed. Have a good day";
      log_msg($conn, $sid, "BOT", $msg);
      respond($msg, [btn("Start over", "RESTART")], "END");
    }
    $msg = "Choose an option.";
    log_msg($conn, $sid, "BOT", $msg);
    respond($msg, [btn("Start over", "RESTART"), btn("Close chat", "DONE")]);

  default:
    $_SESSION["state"] = "START";
    $msg = "Resetting chatbot…";
    log_msg($conn, $sid, "BOT", $msg);
    respond($msg, [btn("Start", "START")], "START");
}
