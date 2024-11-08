<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Comparison</title>
</head>
<body>
    <h2>Image Comparison</h2>
    <form id="imageForm">
        <input type="file" id="image1Input" accept="image/*" onchange="previewImage(event, 'previewImage1')">
        <div id="previewImage1"></div>

        <input type="file" id="image2Input" accept="image/*" onchange="previewImage(event, 'previewImage2')">
        <div id="previewImage2"></div>

        <button type="button" onclick="compareImages()">Compare Images</button>
    </form>

    <script>
        function previewImage(event, previewId) {
            var file = event.target.files[0];
            var reader = new FileReader();

            reader.onload = function(event) {
                var imgElement = document.createElement("img");
                imgElement.src = event.target.result;
                imgElement.style.maxWidth = "200px"; // Adjust the max width as needed
                document.getElementById(previewId).innerHTML = "";
                document.getElementById(previewId).appendChild(imgElement);
            };

            reader.readAsDataURL(file);
        }

        function compareImages() {
            var image1Input = document.getElementById("image1Input");
            var image2Input = document.getElementById("image2Input");

            if (image1Input.files.length === 0 || image2Input.files.length === 0) {
                alert("Please upload two images to compare.");
                return;
            }

            var file1 = image1Input.files[0];
            var file2 = image2Input.files[0];

            var reader1 = new FileReader();
            var reader2 = new FileReader();

            reader1.onload = function(event) {
                var base64Image1 = event.target.result;
                reader2.readAsDataURL(file2);

                reader2.onload = function(event) {
                    var base64Image2 = event.target.result;
                    var similarityPercentage = compareBase64Images(base64Image1, base64Image2);
                    alert("Similarity Percentage: " + similarityPercentage + "%");
                };
            };

            reader1.readAsDataURL(file1);
        }

        function compareBase64Images(base64Image1, base64Image2) {
            // Convert Base64 strings to binary data
            var binaryImage1 = atob(base64Image1.split(',')[1]);
            var binaryImage2 = atob(base64Image2.split(',')[1]);

            // Convert binary data to arrays
            var arrayBuffer1 = new ArrayBuffer(binaryImage1.length);
            var arrayBuffer2 = new ArrayBuffer(binaryImage2.length);
            var view1 = new Uint8Array(arrayBuffer1);
            var view2 = new Uint8Array(arrayBuffer2);

            for (var i = 0; i < binaryImage1.length; i++) {
                view1[i] = binaryImage1.charCodeAt(i);
            }

            for (var j = 0; j < binaryImage2.length; j++) {
                view2[j] = binaryImage2.charCodeAt(j);
            }

            // Calculate similarity percentage
            var similarity = calculateSimilarityPercentage(view1, view2);
            return similarity;
        }

        function calculateSimilarityPercentage(array1, array2) {
            var similarityCount = 0;

            for (var i = 0; i < array1.length; i++) {
                if (array1[i] === array2[i]) {
                    similarityCount++;
                }
            }

            var similarityPercentage = (similarityCount / array1.length) * 100;
            return similarityPercentage.toFixed(2); // Return percentage rounded to 2 decimal places
        }
    </script>
</body>
</html>
