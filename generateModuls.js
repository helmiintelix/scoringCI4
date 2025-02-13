const fs = require('fs');
const path = require('path');

// Function to create folder structure
function createFolderStructure(basePath, moduleName) {
    if (!moduleName) {
        console.error("Nama folder tidak boleh kosong!");
        return;
    }

    const modulePath = path.join(basePath, 'app', 'Modules', moduleName);
    const subFolders = ['Controllers', 'Models', 'Views'];

    try {
        // Create main module folder
        if (!fs.existsSync(modulePath)) {
            fs.mkdirSync(modulePath, { recursive: true });
            console.log(`Folder utama '${modulePath}' berhasil dibuat.`);
        } else {
            console.log(`Folder utama '${modulePath}' sudah ada.`);
        }

        // Create subfolders
        subFolders.forEach(folder => {
            const subFolderPath = path.join(modulePath, folder);
            if (!fs.existsSync(subFolderPath)) {
                fs.mkdirSync(subFolderPath);
                console.log(`Subfolder '${subFolderPath}' berhasil dibuat.`);
            } else {
                console.log(`Subfolder '${subFolderPath}' sudah ada.`);
            }
        });

        // Create folder in /modules
        const JSmodulesPath = path.join(basePath, 'public', 'modules', moduleName);
        if (!fs.existsSync(JSmodulesPath)) {
            fs.mkdirSync(JSmodulesPath, { recursive: true });
            console.log(`Folder '${JSmodulesPath}' berhasil dibuat.`);
        } else {
            console.log(`Folder '${JSmodulesPath}' sudah ada.`);
        }

    } catch (error) {
        console.error(`Terjadi kesalahan: ${error.message}`);
    }
}

// Base path for /app/modules
const basePath = path.join(__dirname);

// Input folder name
defineFolderName();

function defineFolderName() {
    const readline = require('readline').createInterface({
        input: process.stdin,
        output: process.stdout
    });

    readline.question('-Diawali dengan huruf Kapital \n\r-Tidak boleh ada spesial karakter (contoh: _ ) \n\r-Contoh format: "TeamManagement" \n\r-Masukkan nama folder: ', (moduleName) => {
        createFolderStructure(basePath, moduleName);
        readline.close();
    });
}
