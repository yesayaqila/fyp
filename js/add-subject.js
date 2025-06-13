function addSubjectRow(button) {
    const container = button.previousElementSibling;

    const row = document.createElement("div");
    row.className = "subject-row";
    row.innerHTML = `
        <input type="text" placeholder="Darjah" name="darjah[]">
        <input type="text" placeholder="Kelas" name="kelas[]">
        <input type="text" placeholder="Subjek" name="subjek[]">
        <button class="delete-row-btn" onclick="this.parentElement.remove()"><i class="fas fa-trash"></i></button>
    `;

    container.appendChild(row);
}