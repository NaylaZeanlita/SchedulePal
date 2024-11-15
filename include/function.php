<?php
include 'connection.php';

function logOut()
{
  session_start();
  session_unset();
  session_destroy();
  header("Location: login.php");
  exit();
}

function deleteFakultas($id, $conn)
{
  $sql = "DELETE FROM fakultas WHERE id_fakultas ='$id'";
  mysqli_query($conn, $sql);
  return;
}

function editFakultas($id, $nama, $conn)
{
  $sql = "UPDATE fakultas SET nama_fakultas='$nama' WHERE id_fakultas='$id'";
  if (isset($id, $nama)) {
    mysqli_query($conn, $sql);
    return ["message" => "Fakultas berhasil diupdate"];
  } else {
    return ["message" => "Data tidak lengkap"];
  }

}

function tambahFakultas($id, $nama, $conn)
{
  $sql = "SELECT * FROM fakultas WHERE id_fakultas='$id'";
  $hasil = mysqli_query($conn, $sql);
  if (isset(mysqli_fetch_assoc($hasil)['id_fakultas'])) {
    return ["message" => "Fakultas sudah ada"];
  } else {
    $sql = "INSERT INTO fakultas (id_fakultas, nama_fakultas) VALUES ('$id', '$nama')";
    mysqli_query($conn, $sql);
    return ["message" => "Fakultas berhasil ditambahkan"];
  }
}

function login($conn, $username, $password)
{
  $sql = "SELECT * FROM users WHERE username='$username'";
  $result = mysqli_query($conn, $sql);
  if ($result->num_rows === 0) {
    return [
      "status" => false,
      "message" => "username not found!"
    ];
  }
  $sql = "SELECT * FROM users WHERE username='$username'";
  $result = mysqli_query($conn, $sql);
  $user = mysqli_fetch_assoc($result);

  if (!password_verify($password, $user['password'])) {
    return [
      "status" => false,
      "message" => "Password is incorrect!"
    ];
  }

  return [
    "status" => true,
    "message" => "Successfully logged in!",
    "NIM" => $user['NIM'],
    "username" => $user['username'],
    "role" => $user['role'],
    "fakultas" => $user['fakultas'],
  ];
}

function hitungJumlahFakultas($conn)
{
  $sql = "SELECT COUNT(*) AS jumlah FROM fakultas";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  return $row['jumlah'];
}

function requestSchedule($id_acara = null, $status = null, $conn = null) 
    if ($id_acara !== null && $status !== null && $conn !== null) {
        $query = "UPDATE schedule SET status = ? WHERE id_acara = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $status, $id_acara);
        
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }
}
function jumlahScheduleSetuju($conn)
{
  $sql = "SELECT COUNT(status) FROM schedule WHERE status='true'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  return $row['COUNT(status)'];
}

function jumlahScheduleTolak($conn)
{
  $sql = "SELECT COUNT(status) FROM schedule WHERE status='False'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  return $row['COUNT(status)'];
}
?>
