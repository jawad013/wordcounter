function updateColorDetails(value) {
  const preview = document.getElementById('colorPreview');
  const details = document.getElementById('colorDetails');

  try {
    preview.style.backgroundColor = value;

    const temp = document.createElement("div");
    temp.style.color = value;
    document.body.appendChild(temp);
    const computedColor = getComputedStyle(temp).color;
    document.body.removeChild(temp);

    const rgbMatch = computedColor.match(/\d+/g);
    const r = +rgbMatch[0], g = +rgbMatch[1], b = +rgbMatch[2];

    const hex = "#" + [r, g, b].map(x => x.toString(16).padStart(2, '0')).join('').toUpperCase();

    const rPerc = r / 255, gPerc = g / 255, bPerc = b / 255;
    const max = Math.max(rPerc, gPerc, bPerc), min = Math.min(rPerc, gPerc, bPerc);
    let h = 0, s = 0, l = (max + min) / 2;

    if (max !== min) {
      const d = max - min;
      s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
      switch (max) {
        case rPerc: h = (gPerc - bPerc) / d + (gPerc < bPerc ? 6 : 0); break;
        case gPerc: h = (bPerc - rPerc) / d + 2; break;
        case bPerc: h = (rPerc - gPerc) / d + 4; break;
      }
      h *= 60;
    }

    const hsl = `hsl(${Math.round(h)}, ${Math.round(s * 100)}%, ${Math.round(l * 100)}%)`;
    const rgb = `rgb(${r}, ${g}, ${b})`;

    details.innerHTML = `
      <div><span>RGB:</span> <code>${rgb}</code> <button class="copy-btn" onclick="copyText('${rgb}')">Copy</button></div>
      <div><span>HEX:</span> <code>${hex}</code> <button class="copy-btn" onclick="copyText('${hex}')">Copy</button></div>
      <div><span>HSL:</span> <code>${hsl}</code> <button class="copy-btn" onclick="copyText('${hsl}')">Copy</button></div>
    `;
  } catch {
    details.innerHTML = `<div style="color:red;">Invalid color format.</div>`;
    preview.style.backgroundColor = '#eee';
  }
}

function copyText(text) {
  navigator.clipboard.writeText(text).then(() => {
    alert("Copied: " + text);
  }).catch(() => {
    alert("Failed to copy");
  });
}

document.getElementById('colorInput').addEventListener('input', function () {
  updateColorDetails(this.value.trim());
});

document.getElementById('realColorPicker').addEventListener('input', function () {
  const hex = this.value;
  document.getElementById('colorInput').value = hex;
  updateColorDetails(hex);
});

window.addEventListener('DOMContentLoaded', () => {
  const defaultColor = '#00bfff';
  document.getElementById('colorInput').value = defaultColor;
  updateColorDetails(defaultColor);
});
