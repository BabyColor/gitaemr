<?
//----Identity--------
$lanIdentitiy="Identitas";
$lanPermission="Ijin";
$lanPatientID="No.RM";
$lanName="Nama";
$lanFName="Nama Depan";
$lanMName="Nama Tengah";
$lanLName="Nama Belakang";
$lanTitle="Gelar";
$lanUserID= "ID Pengguna";
$lanUserName= "User Name";
$lanPassword= "Kata sandi";
$lanPName= "Nama Bali";
$lanFName= "First Name";
$lanMName= "Middle Name";
$lanLName= "Last Name";
$lanNName= "Nick Name";
$lanDOB= "Tanggal Lahir";
$lanSex= "Jenis Kelamin";
$lanPosition= "Jabatan";
$lanJob= "Jenis";
$lanDepartement= "Departement";
$lanSpecialty= "Spesialis";
$lanSubSpecialty= "Sub Spesialis";
$lanAddress= "Alamat";
$lanDesa= "Desa";
$lanDistrict= "Kecamatan";
$lanRegency= "Kabupaten";
$lanProvince= "Provinsi";
$lanCountry= "Negara";
$lanMarital= "Status";
$lanStaffID= "Nomer Anggota";
$lanEmail= "Surel";
$lanCell= "Nomer HP";
$lanIDType= "Jenis Identitas";
$lanIDCard= "Nomer KITAS";
$lanPatient= "Pasien";
$lanAang= "Avatar";
$lanSignUpDate= "Mendaftar pada";
$lanLastActiveTime= "Terakhir aktif pada";
$lanLastActiveIP=  "IP aktif terakhir";
$lanLastActiveInfo= "Detail aktif terakhir";
$lanLastPasswordChange= "Terakhir kali ganti password";
$lanPasswordBestBefore= "Password kadaluarsa pada";
$lanPatient= "Pasien";
$lanLog= "Kayu";
$lanSymtomp= "Gejala";
$lanMedTest= "Pemeriksaan Penunjang";
$lanDiagnosis= "Diagnosis";
$lanMedicine= "Pengobatan";
$lanMedProcedure= "Tindakan";
$lanBilling= "Pembayaran";
$lanFacility= "Faskes";
$lanBackup= "Pencadangan";
$lanPartner= "Kerja Sama";
$lanBanned= "Skorsing";
$lanBantime= "Di skor sampai dengan";
$lanActive= "Aktif";
$lanReasonOfInnactive= "Alasan tidak aktif";
$lanStatus = "Status";
$lanRegID="SIP";
$lanSignUp="Daftar";
$lanOccupation="Pekerjaan";
$lanOldPass="Kata sandi lama";
$lanConfirmPass="Ulangi kata sandi";

//----Sex---
$lanMale="Laki-Laki";
$lanFemale="Perempuan";
$lanAlien="Alien";
$lanUnidentified="Tidak diketahui";

//---Occupatient---
$lanServices='Pelayanan';
$lanAccounting='Akunting';
$lanManagement='Manajemen';
$lanLogistic='Logistik';
$lanSales='Penjualan';
$lanMarketing='Pemasaran';
$lanPhysician='Dokter';
$lanNurse='Perawat';
$lanMidWife='Bidan';
$lanMedicalAssistance='Asisten';
$lanRadiographer='Radiografer';
$lanAnalyst='Analis';
$lanChasier='Kasir';
$lanBilling='Pembayaran';
$lanAccountant='Akuntan';
$lanCFO='CFO';
$lanGP='Dokter Umum';
$lanUrologist='Urologi';
$lanInternist='Penyakit Dalam';
$lanCardiologist='Jantung dan Pembuluh Darah';
$lanGeneralSurgeon='Bedah Umum';
$lanDigestiveSurgeon='Bedah Digestif';
$lanOrthopedic='Ortopedi';
$lanPediatric='Anak';

//---- Marital-----
$lanSingle='Lajang';
$lanMarried='Menikah';
$lanWidowed='Pasangan Meninggal';
$lanComplicated='Ribet';

//----ID------
$lanCountryStandard='KTP';  //---This should be your country's ID card
$lanDriverLicense1='SIM';
$lanStudentID='Kartu Pelajar';
$lanPassport='Passport';

//----Relationship------
$lanChild='Anak';
$lanSibling='Saudara Kandung';
$lanParent='Orangtua';
$lanGrandParrent='Kakek/Nenek';
$lanGrandChild='Cucu';
$lanSpouse='Suami/Istri';
$lanLover='Pacar';
$lanFriends='Teman';
$lanAcquaintance='Kenalan';
$lanCoworker='Rekan Kerja';
$lanEmployee='Karyawan';
$lanEmployer='Atasan';
$lanMoreThanFriendButLessThanLover='TTM';
$lanFriendZone='Friend Zone';
$lanWaifu='Waifu';
$lanDivorced='Bercerai';
$lanOther='Orang Lain';
$lanCousin='Sepupu';
$lanOtherFamilly='Keluarga';
$lanGreatGrandParent='Buyut';
$lanGreatGrandChildern='Cicit';
$lanAncestor='Bethara Hyang Guru';

//----------Common------------
$lanError="Terjadi kesalahan";
$lanOK="Berhasil";
$lanWarning="Peringatan";
$lanFieldRequired='Kolom ini harus diisi';
$lanLoginUser="Pengguna";
$lanLoginPass="Kata Sandi";
$lanLogin="Masuk";
$lanEdit="Ubah";
$lanSubmit="Kirim";
$lanCancel="Batalkan";
$lanReset="Hapus";

////////////////////////////////////////////////////
////////////  MESSAGE /////////////////////////////
/////////////////////////////////////////////////

//-----------------ERROR----------------------
$lanSignUpErrorMessage0T="Pengulangan Kata Sandi Tidak Cocok";
$lanSignUpErrorMessage0C="Kata sandi yang anda masukkan pada kolom kata sandi dan konfirmasi tidak sama";
$lanSignUpErrorMessage1T="Kolom Penting Masih Kosong";
$lanSignUpErrorMessage1C="Kolom berikut harus diisi :";
$lanSignUpErrorMessage1A="Silahkan diisi ulang";
$lanSignUpErrorMessage2T="Email Sudah Terdaftar";
$lanSignUpErrorMessage2C="Data berikut :";
$lanSignUpErrorMessage2A="Sudah pernah didaftarkan, silahkan <a href=". htmlspecialchars( $_SERVER['PHP_SELF'] ) ."?mod=gita_login&job=2>Masuk</a> atau <a href=". htmlspecialchars( $_SERVER['PHP_SELF'] ) ."?mod=gita_login&job=3>Minta Bantuan Masuk</a>";
$lanSignUpErrorMessage3T="Kesalahan Pengisian Kolom";
$lanSignUpErrorMessage3C="Terdapat kesalahan pengisian / karakter tidak valid pada kolom berikut :";

//----------------Warning
$lanRawPasswordT="Password Tidak Di Proteksi";
$lanRawPasswordC="Password disimpan secara gamblang pada database.<br>Sewajarnya password di-enkripsi terlebih dahulu sebelum disimpan.<br>Hubungi administrator untuk info lebih lanjut.";

//-----------------Success----------------------
$lanSignUpT="Pendaftaran Berhasil";
$lanSignUp0="Pengguna berhasil di tambahkan, silahkan Login menggunakan email/username dan password yang anda daftarkan<br>$lanBackToLogin";
$lanSignUp1="Pengguna berhasil di tambahkan, mohon menunggu moderator untuk mengaktifkan akun anda<br>$lanBackToLogin";
$lanSignUp2="Pengguna berhasil di tambahkan, silahkan cek email anda untuk petunjuk cara mengaktifkan akun anda<br>$lanBackToLogin";

//-------------------Login------------------------
$lanBackToLogin="Kembali ke <a href=". htmlspecialchars( $_SERVER['PHP_SELF'] ) ."?mod=gita_login&job=2>Masuk</a> atau <a href=". htmlspecialchars( $_SERVER['PHP_SELF'] ) ."?mod=gita_login&job=3>Minta Bantuan Masuk</a>";
$lanLoginTitle="Masuk";
$lanLoginNoUser="Pengguna tidak terdaftar";
$lanLoginWrongPass="Kata sandi yang dimasukkan salah";
$lanLoginSuccessT="Login Berhasil";
$lanLoginSuccessC="Selamat Datang";
$lanLoginFailedT="Login Gagal";
?>
