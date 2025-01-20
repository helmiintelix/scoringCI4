function hitung_kredit(besar_pinjaman, jangka, bunga) {
  let bunga_bulan = bunga / 12 / 100;
  let pembagi = 1 - 1 / Math.pow(1 + bunga_bulan, jangka);
  let hasil = besar_pinjaman / (pembagi / bunga_bulan);
  return hasil;
}

function hitung_cicilan_baru(besar_pinjaman, jangka, bunga) {
  let anuitas = hitung_kredit(besar_pinjaman, jangka, bunga);

  let ang_bunga = besar_pinjaman * (bunga / 100);
  let ang_pokok = anuitas - ang_bunga;
  let cicilan = ang_bunga + ang_pokok;

  return cicilan;
}
