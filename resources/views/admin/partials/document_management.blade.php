<script>
        const documentList = document.querySelector("tbody");
        const addMoreButton = document.getElementById("add-more");

        // Function to create a new row with document name, upload input, and delete button
        function createRow(documentName) {
            const newRow = document.createElement("tr");
            newRow.innerHTML = `
                <th scope="row">${documentList.children.length + 1}</th>
                <td>${documentName}</td>
                <td>
                    <div>
                        <input type="file" accept=".pdf, .doc, .docx" class="document-upload-input">
                        <input type="button" class="btn btn-primary upload-button" value="Upload"/>
                    </div>
                    <button class="btn btn-danger delete-button">Delete</button>
                </td>
            `;
            documentList.appendChild(newRow);
        }

        // Add event listener to "Add More" button
        addMoreButton.addEventListener("click", function() {
            const documentName = "New Document"; // You can set the default document name here or fetch it from user input
            createRow(documentName);
        });

        // Add event listener to upload buttons
        documentList.addEventListener("click", function(event) {
            const target = event.target;
            if (target.classList.contains("upload-button")) {
                const fileInput = target.previousElementSibling;
                const file = fileInput.files[0]; // Get the selected file
                // Upload logic here (e.g., using AJAX)
                console.log("Uploading file:", file);
            }
        });

        // Add event listener to upload buttons
        documentList.addEventListener("click", function(event) {
            const target = event.target;
            if (target.classList.contains("upload-button")) {
                const fileInput = target.previousElementSibling;
                const file = fileInput.files[0]; // Get the selected file
                
                if (file) {
                    const formData = new FormData();
                    formData.append('file', file);
                    formData.append('category_id', "{{ $objPost->category }}");
                    formData.append('post_id', "{{ $objPost->id }}");
                    // Reference to the upload button
                    const uploadButton = target;

                    // Disable the upload button
                    uploadButton.disabled = true;
                    // Send the file data to the server using AJAX
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', "{{route('admin.doc_update', $objPost->id)}}");
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}'); // Include CSRF token if using Laravel CSRF protection
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            toastr.success('File uploaded successfully');
                            
                            uploadButton.disabled = true;
                        } else {
                            toastr.error('File upload failed');
                           uploadButton.disabled = false;
                        }
                    };
                    xhr.send(formData);
                } else {
                    console.error('No file selected');
                    // Optionally, you can display an error message to the user
                }
            }
        });
        // Add event listener to dynamically created delete buttons
        documentList.addEventListener("click", (event) => {
            if (event.target.classList.contains("delete-button")) {
                swal({
                title: "Are you sure?",
                text: "You will not be able to recover this data!",
                type: "error",
                showCancelButton: true,
                dangerMode: true,
                cancelButtonClass: '#DD6B55',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Delete!',
            }, function(result) {
                if (result) {
                var documentId = '';
                const row = event.target.closest("tr");
                const hiddenInput = event.target.closest("td").querySelector("input[type=hidden]");
                if (hiddenInput) {
                    // Access the value of the hidden input field
                 documentId = hiddenInput.value;
                }
                const formData = new FormData();
                    
                    formData.append('category_id', "{{ $objPost->category }}");
                    formData.append('post_id', "{{ $objPost->id }}");
                    formData.append('file_id', documentId);
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', "{{route('admin.doc_delete', $objPost->id)}}");
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}'); // Include CSRF token if using Laravel CSRF protection
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            toastr.success('File deleted successfully');
                            
                            row.remove();
                        } else {
                            toastr.error('File delete failed');
                           
                        }
                    };
                    xhr.send(formData);
                    }else{
                        return false;
                    }

                });
                } else {
                    console.error('No file to delete');
                    // Optionally, you can display an error message to the user
                }
                
            
        });
    </script>