<?php

require 'dbcon.php';


function error422($message)
{
    $data = [
        'status' => 422,
        'message' => $message,
    ];
    header("HTTP/1.0 422 Unproccessable Entity");
    echo json_encode($data);
    exit();
}

// database functions
function storeCurrentStage($current_stage, $userno)
{
    global $conn;
    $query = "UPDATE chatbot_flow set current_stage = $current_stage , updated_at = NOW() where phone = $userno";
    mysqli_query($conn, $query);
}

function getCurrentStage($userno)
{
    global $conn;

    $query = "SELECT * from chatbot_flow where phone = $userno";
    $query_run = mysqli_query($conn, $query);
    $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);
    if (!$res) {
        $query = "INSERT INTO chatbot_flow(phone,current_stage) Values ('$userno','-1')";
        $query_run = mysqli_query($conn, $query);
    }
    return $res[0];
}

function createTicket($userno, $username, $request)
{
    global $conn;
    $query = "INSERT INTO tickets (owner_name,owner_phone,status,problem,created_at,updated_at) VALUES ('$username','$userno','New','$request',NOW(),NOW())";
    mysqli_query($conn, $query);
}


function getTicketId($userno)
{
    global $conn;
    $query = "SELECT id FROM tickets where owner_phone = $userno AND status = 'New'
    ORDER BY started_at DESC 
    LIMIT 1;";

    $query_run = mysqli_query($conn, $query);
    $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);
    return $res[0]['id'];
}

function checkTicket($userno)
{
    global $conn;

    $query = "SELECT id FROM tickets where owner_phone = $userno AND status <> 'Closed'";
    $query_run = mysqli_query($conn, $query);
    $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);
    return $res[0]['id'];
}

// function checkUser($userno)
// {
//     global $conn;

//     $query = "SELECT id FROM tickets where owner_phone = $userno AND status <> 'Closed'";
//     $query_run = mysqli_query($conn, $query);
//     $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);
//     return $res[0]['id'];
// }

function getClients($userno)
{
    global $conn;

    $query = "SELECT * FROM students WHERE phone = $userno";
    $query_run = mysqli_query($conn, $query);
    $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);
    return $res[0];
}

function getActivities()
{
    global $conn;

    $query = "SELECT * FROM activities";
    $query_run = mysqli_query($conn, $query);
    $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);
    return $res;
}


function resetPassword($password, $userno)
{
    global $conn;

    if (preg_match('/\p{Arabic}/u', $password)) {
        sendTextMessage('كلمة السر يجب ألا تقل عن خمسة حروف أو أرقام بالإنجليزية\\n برجاءادخال كلمة السر مرة اخري', $userno);
    } else {
        if (mb_strlen(trim($password)) > 4) {

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $query = "UPDATE students SET password = '$hashedPassword' WHERE phone = $userno";
            mysqli_query($conn, $query);

            sendTextMessage('تم  تغيير كلمة السر بنجاح', $userno);
            storeCurrentStage(61, $userno);
            previousMessage($userno);
        } else {
            sendTextMessage('كلمة السر يجب ألا تقل عن خمسة حروف أو أرقام بالإنجليزية\\n برجاءادخال كلمة السر مرة اخري', $userno);
        }
    }
}



// send messages functions
function sendNewListToUser($to, $username)
{
    $bodyText = 'اقدر اقدم لحضرتك معلومات اكتر عن الخدمات في السنتر من فضلك اختر من القائمة ';

    $data = '{
        "messaging_product": "whatsapp",
        "recipient_type": "individual",
        "to": "' . $to . '",
        "type": "interactive",
        "interactive": {
            "type": "list",
            "header": {
                "type": "text",
                "text": "' . $username . ' أهلا "
            },
            "body": {
                "text": "' . $bodyText . '"
            },
            
            "action": {
                "button": "من فضلك اختار",
                "sections": [
                    {
                        "title": "من فضلك اختار:",
                        "rows": [
                            
                            {
                                "id": "scheduleid",
                                "title": "الجدول الدراسي",
                                
                            },
                            {
                                "id": "activitiesid",
                                "title": "الأنشطة المتاحة",
                                
                            },
                            {
                                "id": "learnid",
                                "title": "شروحات",
                                
                            },
                            {
                                "id": "questionsid",
                                "title": "اسئلة متكررة",
                                
                            },
                            {
                                "id": "supportticketid",
                                "title": "طلب دعم فني",
                                
                            },
                            {
                                "id": "resetpasswordid",
                                "title": "تغيير باسورد",
                            },
                            {
                                "id": "endchatid",
                                "title": "انهاء المحادثة",
                                
                            }
                        ]
                    }
                    
                ]
            }
        }
    }';

    storeCurrentStage(0, $to);
    sendMessageToWT($data);
}


function sendLearnVideoListToUser($to)
{
    $bodyText = 'اهم الفيديوهات عن السنتر ';

    $data = '{
        "messaging_product": "whatsapp",
        "recipient_type": "individual",
        "to": "' . $to . '",
        "type": "interactive",
        "interactive": {
            "type": "list",
            "body": {
                "text": "' . $bodyText . '"
            },
            "action": {
                "button": "من فضلك اختار",
                "sections": [
                    {
                        "title": "من فضلك اختار:",
                        "rows": [
                            
                            {
                                "id": "video1id",
                                "title": "Ved1",
                                
                            },
                            {
                                "id": "video2id",
                                "title": "Ved2",
                                
                            },
                            {
                                "id": "video3id",
                                "title": "Ved3",
                                
                            },
                            {
                                "id": "video4id",
                                "title": "Ved4",
                                
                            },
                            {
                                "id": "video5id",
                                "title": "Ved5",
                                
                            },
                            {
                                "id": "video6id",
                                "title": "Ved6",
                                
                            },
                            {
                                "id": "returnpreviouslistid",
                                "title": "القائمة السابقة"
                                
                            }
                            
                        ]
                    }
                    
                ]
            }
        }
    }';

    sendMessageToWT($data);
}


// function sendSahlFeaturesToWT($to)
// {
//     $contentText =   "صمم فاتورتك بنفسك \\n" .
//         "اختر من بين عدة تصميمات رائعة ومختلفة لفاتورة المبيعات وعرض الأسعار وإيصال الصرف والقبض وغيرها. \\n أيضا يمكنك تعديل شكل الفاتورة كما يحلو لك باستخدام برنامج مايكروسوفت أكسل ، ضع اللوجو الخاص بشركتك ، بياناتها ، غير شكلها وحجمها وخطوطها ، ثم احفظ الملف ، وسيقوم البرنامج باستخدام التصميم الجديد فى طباعة الفواتير. \\n";

//     sendImageMessage('https://images.pexels.com/photos/17215592/pexels-photo-17215592/free-photo-of-wood-landscape-sunset-water.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', $contentText, $to);
// }

function sendTextMessage($contentMsg, $to)
{
    $data = '{
        "messaging_product": "whatsapp",
        "to": "' . $to . '",
        "type": "text",
        "text": {
            "preview_url": true,
            "body": "' . $contentMsg . '",
        }
    }';

    sendMessageToWT($data);
}

function sendVideoMessage($contentMsg, $videoCaption, $to)
{
    $data = '{
        "messaging_product": "whatsapp",
        "to": "' . $to . '",
        "type": "video",
        "video": {
            "link": "' . $contentMsg . '",
            "caption": "' . $videoCaption . '"
        }
    }';


    sendMessageToWT($data);
}

function sendImageMessage($contentMsg, $imageCaption, $to)
{
    $data = '{
        "messaging_product": "whatsapp",
        "to": "' . $to . '",
        "type": "image",
        "image": {
           "link": "' . $contentMsg . '",
           "caption": "' . $imageCaption . '"
        }
    }';

    sendMessageToWT($data);
}

function sendDocumentMessage($contentMsg, $documentCaption, $to)
{
    $data = '{
        "messaging_product": "whatsapp",
        "to": "' . $to . '",
        "type": "document",
        "document": {
            "link": "' . $contentMsg . '",
            "caption":"' . $documentCaption . '",
            "filename": "Your Schedule"
        }
    }';

    storeCurrentStage(11, $to);
    sendMessageToWT($data);
}




function previousMessage($to)
{
    $data = '{
        "messaging_product": "whatsapp",
        "recipient_type": "individual",
        "to": "' . $to . '",
        "type": "interactive",
        "interactive": {
            "type": "button",
            "body": {
                "text":  "اختر من التالي:"
            },
            "action": {
                "buttons": [
                    {
                        "type": "reply",
                        "reply": {
                            "id": "returnpreviouslistid",
                            "title": "القائمة السابقة"
                        }
                    },
                    {
                        "type": "reply",
                        "reply": {
                            "id": "mainlistid",
                            "title": "القائمة الرئيسية"
                        }
                    },
                    {
                        "type": "reply",
                        "reply": {
                            "id": "sahlendchatid",
                            "title": "انهاء المحادثة"
                        }
                    }
                ]
            }
        }
    }';

    sendMessageToWT($data);
}

function endChatMessage($to)
{
    storeCurrentStage(-1, $to);
    sendTextMessage('شكرا لتواصلكم معنا ', $to);
}

function sendMessageToWT($body)
{

    $phoneid = "102394036252882";
    $WhatsAppToken = 'EAACaVLsLEkkBO7sP6luitCtFSldfMTg0KLsMzen1ibz3lCc0HgYGn9URDlJ5q7GVlOLVKfOxhozXQjvXNyrbiaZApjrhTbJj2ELHf1nBSiz1GFml6utDqXzptZCSYnLvScSp7vlJGxKiXgDJTm4ZBDjD69pI1tjNfhT3FgFb2VZC17OMTpOd8FiHqs0fzuykTJKwm3sgRS7jVOg5JpMZD';
    $api_version = 'v17.0';
    $endpoint = "https://graph.facebook.com/{$api_version}/{$phoneid}/messages";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: Bearer {$WhatsAppToken}",
        "Content-Type: application/json"
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}



// function mainService($to)
// {
    
// 	$welcomeStr =  "```📢 مرحبا بك في سهل  📢 ```\nمن فضلك اختر الخدمات التالية\n";

// 	$str = $welcomeStr .
// 	"\n" .
// 	"1️⃣ : عميل جديد\n" .
// 	"2️⃣ : عميل حالي\n" .
// 	"3️⃣ : انهاء المحادثة\n" ;


// 	// "1️⃣ : Sahl signup.\n" .
// 	// "2️⃣ : About Sahl.\n" .
// 	// "3️⃣ : Pricing.\n" .
// 	// "4️⃣ : Support.\n" . 
// 	// "5️⃣ : Close chat.\n" ;
// 	// "6️⃣ : Send Video.\n" .
// 	// "7️⃣ : Send Contact.\n" .
// 	// "8️⃣ : Send Random Sentence.\n" .
// 	// "9️⃣ : Send Random Joke.\n" .
// 	// "🔟 : Send Random Image.\n";
    
// 	storeCurrentStage(0,$to);
// 	sendMessageToWT($str,$to);
// }

// function currentClientService($to)
// {
    
// 	$welcomeStr =  "من فضلك اختر أحد الخدمات التالية\n";

// 	$str = $welcomeStr .
// 	"\n" .
// 	"1️⃣ : طلب دعم فني\n" .
// 	"2️⃣ : أسئلة متكررة\n" .
// 	"3️⃣ : انهاء المحادثة\n" ;


// 	// "1️⃣ : Sahl signup.\n" .
// 	// "2️⃣ : About Sahl.\n" .
// 	// "3️⃣ : Pricing.\n" .
// 	// "4️⃣ : Support.\n" . 
// 	// "5️⃣ : Close chat.\n" ;
// 	// "6️⃣ : Send Video.\n" .
// 	// "7️⃣ : Send Contact.\n" .
// 	// "8️⃣ : Send Random Sentence.\n" .
// 	// "9️⃣ : Send Random Joke.\n" .
// 	// "🔟 : Send Random Image.\n";
    
// 	storeCurrentStage(2,$to);
// 	sendMessageToWT($str,$to);
// }

// function newClientService($to)
// {
    
// 	$welcomeStr =  "من فضللك اختر أحد الخدمات التالية\n";

// 	$str = $welcomeStr .
// 	"\n" .
// 	"1️⃣ : جولة سريعة\n" .
// 	"2️⃣ : تحميل سهل\n" .
// 	"3️⃣ : اسعار باقات الاشتراك\n". 
// 	"4️⃣ : أسئلة متكررة\n" . 
// 	"5️⃣ : طلب دعم فني\n" .
//  "6️⃣ : الموزعيين\n" .
// 	"7️⃣ : مميزات سهل\n" .
//     "8️⃣ : إنهاء المحادثة\n" ;


// 	// "1️⃣ : Sahl signup.\n" .
// 	// "2️⃣ : About Sahl.\n" .
// 	// "3️⃣ : Pricing.\n" .
// 	// "4️⃣ : Support.\n" . 
// 	// "5️⃣ : Close chat.\n" ;
// 	// "6️⃣ : Send Video.\n" .
// 	// "7️⃣ : Send Contact.\n" .
// 	// "8️⃣ : Send Random Sentence.\n" .
// 	// "9️⃣ : Send Random Joke.\n" .
// 	// "🔟 : Send Random Image.\n";
    
// 	storeCurrentStage(1,$to);
// 	sendMessageToWT($str,$to);
// }
