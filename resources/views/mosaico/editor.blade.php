<iframe src="{{ asset('mosaico/src/html/editor.html') }}" style="width: 100%; height: 100vh; border: none;"></iframe>
<button onclick="downloadTemplateJson()" class="your-button-class">Download Template</button>
<input type="file" id="templateUploader" style="display: none" accept=".json" onchange="loadTemplate(this)">
<button onclick="document.getElementById('templateUploader').click()" class="your-button-class">Upload Template</button>

<script>
function downloadTemplateJson() {
    if (window.viewModel && window.viewModel.exportJSON) {
        const templateJson = window.viewModel.exportJSON();
        
        const blob = new Blob([JSON.stringify(templateJson, null, 2)], { type: 'application/json' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'mosaico-template.json';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        a.remove();
    } else {
        console.error('Mosaico viewModel not found');
    }
}

function loadTemplate(input) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            try {
                const templateData = JSON.parse(e.target.result);
                // Load the template into Mosaico's editor
                if (window.viewModel && window.viewModel.loadJSON) {
                    window.viewModel.loadJSON(templateData);
                } else {
                    console.error('Mosaico viewModel not found');
                }
            } catch (error) {
                console.error('Error parsing template JSON:', error);
                alert('Invalid template file');
            }
        };
        reader.readAsText(file);
    }
}

function saveTemplateToServer() {
    const templateJson = window.viewModel.exportJSON();
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/save-template', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ template: templateJson })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Template saved:', data);
        alert('Template saved successfully!');
    })
    .catch(error => {
        console.error('Error saving template:', error);
        alert('Error saving template');
    });
}

window.onload = function() {
    const editorWindow = document.querySelector('iframe').contentWindow;
    window.viewModel = editorWindow.viewModel;
}
</script>

