<?php
include 'config.php';
if (!isset($_SESSION['user_email'])) { header("Location: login.php"); exit(); }
if (!isset($_GET['domain'])) { header("Location: dashboard.php"); exit(); }

$domain = $_GET['domain'];
$user_email = $_SESSION['user_email'];

// Owner validation pipeline via clean server-side cURL
$ch_req = curl_init("$FIREBASE_URL/requests.json?key=$API_KEY");
curl_setopt($ch_req, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch_req, CURLOPT_SSL_VERIFYPEER, false);
$reqs = curl_exec($ch_req);
curl_close($ch_req);

$reqs_data = json_decode($reqs, true);
$is_owner = false;

if($reqs_data) {
    foreach($reqs_data as $req) {
        if($req['domain'] == $domain && $req['email'] == $user_email && $req['status'] == 'success') {
            $is_owner = true;
            break;
        }
    }
}
if(!$is_owner) { die("<h2 style='color:red; text-align:center; margin-top:50px;'>Access Denied!</h2>"); }

// Proxy engines for local data upload processing pipelines
if (isset($_GET['action']) && $_GET['action'] == 'upload' && isset($_FILES['file'])) {
    $ch = curl_init("$HF_API/file-upload-domin/$domain/");
    $cfile = new CURLFile($_FILES['file']['tmp_name'], $_FILES['file']['type'], $_FILES['file']['name']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ['file' => $cfile]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    echo curl_exec($ch);
    curl_close($ch);
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'get_file') {
    $file_name = $_GET['file_name'];
    $ch = curl_init("$HF_API/$domain/$file_name");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    echo curl_exec($ch);
    curl_close($ch);
    exit();
}

if (isset($_GET['delete'])) {
    $file_to_del = $_GET['delete'];
    $ch = curl_init("$HF_API/delete-file/$domain/?filename=$file_to_del");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_exec($ch);
    curl_close($ch);
    header("Location: upload.php?domain=$domain");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>VIP Cloud Storage Console</title>
    <link rel="stylesheet" href="style.css?v=3">
    <style>
        .editor-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; padding: 20px; }
        .editor-content { background: white; width: 100%; max-width: 800px; height: 90%; margin: 0 auto; border-radius: 12px; display: flex; flex-direction: column; overflow: hidden; }
        .editor-header { padding: 15px; background: #2b1b54; color: white; display: flex; justify-content: space-between; align-items: center; }
        .editor-textarea { flex: 1; width: 100%; padding: 15px; border: none; outline: none; font-family: monospace; font-size: 14px; resize: none; background: #1e1e1e; color: #d4d4d4; }
        .btn-small { padding: 5px 10px; font-size: 12px; border-radius: 4px; border: none; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="dashboard.php" style="color: #673de6; font-weight:bold; text-decoration:none;">&larr; Back</a>
        <div class="logo" style="font-weight: 900; color: #2b1b54;">FILE MANAGER</div>
    </div>

    <div class="container">
        <div class="card" style="text-align: center;">
            <h2>Manage Files for <?php echo $domain; ?></h2>
            <p style="color: gray; margin-bottom: 20px;">Cluster Target Allocation: <strong style="color: #673de6;"><?php echo $domain; ?></strong></p>
            
            <div style="border: 2px dashed #d1d5db; padding: 20px; border-radius: 12px; margin-bottom: 20px;">
                <input type="file" id="fileInput" multiple style="margin-bottom: 10px;">
                <p>OR <input type="file" id="folderInput" webkitdirectory multiple></p>
            </div>
            <button onclick="uploadFiles()" class="btn-primary" id="uploadBtn">Upload Files</button>
            <p id="status" style="margin-top: 10px; color: #059669; font-weight: bold;"></p>
        </div>

        <div class="card">
            <h3>Active Target Live Resources</h3>
            <table style="width: 100%; margin-top: 10px; text-align: left; border-collapse: collapse;">
                <tr style="background: #f3f4f6;">
                    <th style="padding: 10px; border: 1px solid #e5e7eb;">Resource Name</th>
                    <th style="padding: 10px; border: 1px solid #e5e7eb;">Action Sequence</th>
                </tr>
                <tbody id="fileListBody">
                    <tr><td colspan='2' style='text-align:center; padding:15px;'>Connecting to remote allocation table...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="editor-modal" id="editorModal">
        <div class="editor-content">
            <div class="editor-header">
                <span id="editorFilename">Visual Node Editor</span>
                <div>
                    <button class="btn-small" style="background:#10b981; color:white; margin-right:10px;" onclick="saveFile()">Commit Changes</button>
                    <button class="btn-small" style="background:#ef4444; color:white;" onclick="closeEditor()">Abort</button>
                </div>
            </div>
            <textarea id="fileContent" class="editor-textarea" spellcheck="false"></textarea>
        </div>
    </div>

    <script>
        // JS Client Logic architecture bypass system configuration
        async function fetchFileList() {
            const tbody = document.getElementById('fileListBody');
            try {
                let response = await fetch("<?php echo $HF_API; ?>/list-files/<?php echo $domain; ?>/");
                let data = await response.json();
                tbody.innerHTML = "";
                
                if(data.files && data.files.length > 0) {
                    data.files.forEach(f => {
                        tbody.innerHTML += `<tr>
                            <td style='padding: 10px; border: 1px solid #e5e7eb;'>\${f}</td>
                            <td style='padding: 10px; border: 1px solid #e5e7eb;'>
                                <button onclick="openEditor('\${f}')" class='btn-small' style='background:#673de6; color:white; margin-right:10px;'>Edit</button>
                                <a href='upload.php?domain=<?php echo $domain; ?>&delete=\${f}' style='color:red; text-decoration:none; font-weight:bold;'>Delete</a>
                            </td>
                        </tr>`;
                    });
                } else {
                    tbody.innerHTML = "<tr><td colspan='2' style='padding:15px; text-align:center;'>No structural resources found on root node.</td></tr>";
                }
            } catch(e) {
                tbody.innerHTML = "<tr><td colspan='2' style='padding:15px; text-align:center; color:red;'>Table compilation failed. Remote cloud connection refused.</td></tr>";
            }
        }
        window.onload = fetchFileList;

        async function uploadFiles() {
            const fileInput = document.getElementById('fileInput').files;
            const folderInput = document.getElementById('folderInput').files;
            const statusText = document.getElementById('status');
            const uploadBtn = document.getElementById('uploadBtn');
            let allFiles = [...fileInput, ...folderInput];
            if (allFiles.length === 0) return;

            uploadBtn.innerText = "Syncing Node...";
            uploadBtn.disabled = true;
            let successCount = 0;

            for (let i = 0; i < allFiles.length; i++) {
                let formData = new FormData();
                formData.append("file", allFiles[i]);
                try {
                    let response = await fetch("upload.php?domain=<?php echo $domain; ?>&action=upload", { method: "POST", body: formData });
                    let result = await response.json();
                    if(result.message) successCount++;
                } catch (error) {}
            }
            statusText.innerText = successCount + " assets updated cleanly!";
            setTimeout(() => location.reload(), 1000); 
        }

        async function openEditor(filename) {
            currentEditFile = filename;
            document.getElementById('editorFilename').innerText = "Modifying Asset: " + filename;
            document.getElementById('editorModal').style.display = "block";
            document.getElementById('fileContent').value = "Fetching core bytes...";

            let response = await fetch("upload.php?domain=<?php echo $domain; ?>&action=get_file&file_name=" + filename);
            let content = await response.text();
            document.getElementById('fileContent').value = content;
        }

        function closeEditor() { document.getElementById('editorModal').style.display = "none"; }

        async function saveFile() {
            let content = document.getElementById('fileContent').value;
            let blob = new Blob([content], { type: "text/plain" });
            let formData = new FormData();
            formData.append("file", blob, currentEditFile);
            
            document.getElementById('editorFilename').innerText = "Pushing commit...";
            await fetch("upload.php?domain=<?php echo $domain; ?>&action=upload", { method: "POST", body: formData });
            
            document.getElementById('editorFilename').innerText = "Changes Saved!";
            setTimeout(() => closeEditor(), 1000);
            setTimeout(() => fetchFileList(), 1100);
        }
    </script>
</body>
</html>
document.getElementById('editorFilename').innerText = "Saved Successfully!";
            setTimeout(() => closeEditor(), 1000);
        }
    </script>
</body>
</html>
