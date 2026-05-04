$files = @(
    "app/DTO/AuthDTO/loginDTO.php",
    "app/DTO/AuthDTO/registerDTO.php",
    "app/DTO/AuthDTO/userDTO.php",
    "app/DTO/institusiDTO.php",
    "app/DTO/pengumpulanDTO.php",
    "app/Http/Resources/institusiResource.php",
    "app/Http/Resources/pengumpulanResource.php",
    "app/Models/assessment.php",
    "app/Models/identitas.php",
    "app/Models/institusi.php",
    "app/Models/kategori.php",
    "app/Models/opsiJawaban.php",
    "app/Models/pengumpulan.php",
    "app/Models/pengumpulanJawaban.php",
    "app/Models/pertanyaan.php",
    "app/Repositories/institusiRepository.php",
    "app/Repositories/pengumpulanRepository.php",
    "app/Services/institusiService.php",
    "app/Services/pengumpulanService.php"
)

foreach ($file in $files) {
    $dir = Split-Path $file
    $name = Split-Path $file -Leaf
    $newName = $name.Substring(0,1).ToUpper() + $name.Substring(1)
    
    $tmpName = "$file.tmp"
    $targetName = Join-Path $dir $newName
    
    Write-Host "Renaming $file to $targetName"
    git mv $file $tmpName
    git mv $tmpName $targetName
}

git commit -m "FIX: Hard rename files to PascalCase for Linux compatibility"
git push origin main
