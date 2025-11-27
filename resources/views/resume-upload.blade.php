<!DOCTYPE html>
<html>

<head>
  <title>Upload Resume - Supabase Storage</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
  <h1>Upload Resume to Supabase</h1>

  <form id="uploadForm" enctype="multipart/form-data">
    @csrf
    <input type="file" name="new_resume" id="resume" accept=".pdf" required>
    <button type="submit">Upload</button>
  </form>

  <div id="result"></div>

  <script>
    document.getElementById('uploadForm').addEventListener('submit', async (e) => {
      e.preventDefault();

      const fileInput = document.getElementById('resume');
      const formData = new FormData();
      formData.append('new_resume', fileInput.files[0]);

      try {
        const response = await fetch('/resumes/upload', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: formData
        });

        const data = await response.json();

        if (response.ok) {
          document.getElementById('result').innerHTML = `
          <p style="color: green">✓ Upload successful!</p>
          <p>Filename: ${data.filename}</p>
          <p>URL: <a href="${data.url}" target="_blank">${data.url}</a></p>
      `;
        } else {
          document.getElementById('result').innerHTML = `
          <p style="color: red">✗ Upload failed: ${data.message}</p>
      `;
        }

      } catch (error) {
        document.getElementById('result').innerHTML = `
        <p style="color: red">✗ Error: ${error.message}</p>
    `;
      }
    });


  </script>
</body>

</html>