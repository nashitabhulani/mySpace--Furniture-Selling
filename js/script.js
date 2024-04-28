var LoginForm = document.getElementById("LoginForm");
var RegForm = document.getElementById("RegForm");
var Indicator = document.getElementById("Indicator");


function register() {
    RegForm.style.transform = "translateX(0px)";
    LoginForm.style.transform = "translateX(0px)";
    Indicator.style.transform = "translateX(100px)";
}

function login() {
    RegForm.style.transform = "translateX(500px)";
    LoginForm.style.transform = "translateX(500px)";
    Indicator.style.transform = "translateX(0px)";
}
document.addEventListener('DOMContentLoaded', function() {
    function toggleProfileBox() {
        var profileBox = document.getElementById("profile-box");
        if (profileBox) {
            if (profileBox.classList.contains("hidden")) {
                profileBox.classList.remove("hidden");
            } else {
                profileBox.classList.add("hidden");
            }
        } else {
            console.error("Element with ID 'profile-box' not found.");
        }
    }

    var profileBtn = document.getElementById("profile-btn");
    if (profileBtn) {
        profileBtn.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default action (navigation if it's a link)
            toggleProfileBox();
        });
    } else {
        console.error("Element with ID 'profile-btn' not found.");
    }
});


function loadModel(modelFileName, modelName) {
    console.log("Loading model: " + modelFileName);
    const viewerContainer = document.getElementById('threeJSViewer');
    viewerContainer.style.display = 'block';
    
    // Check if the scene has already been initialized
    if (!window.myScene) {
        initThreeJS(); // Initialize only if it hasn't been initialized before
    } else {
        clearScene(); // Clear the scene if already initialized
    }
    
    // Load the new model
    loadNewModel(modelFileName);
}

function initThreeJS() {
    const scene = new THREE.Scene();
    scene.background = new THREE.Color(0xffffff);
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    camera.position.set(0, 0, 5);
    const renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(window.innerWidth, 500);

    document.getElementById('threeJSViewer').appendChild(renderer.domElement);

    // Setting global access for reuse without reinitialization
    window.myScene = scene;
    window.myCamera = camera;
    window.myRenderer = renderer;

    addLightsAndControls();
}

function clearScene() {
    const scene = window.myScene;
    if (!scene) return;

    // Traverse and dispose geometries, materials and textures
    scene.traverse(object => {
        if (object.isMesh) {
            if (object.geometry) {
                object.geometry.dispose();
            }
            if (object.material) {
                const materialArray = Array.isArray(object.material) ? object.material : [object.material];
                materialArray.forEach(material => {
                    if (material.map) material.map.dispose();
                    if (material.lightMap) material.lightMap.dispose();
                    if (material.bumpMap) material.bumpMap.dispose();
                    if (material.normalMap) material.normalMap.dispose();
                    if (material.specularMap) material.specularMap.dispose();
                    if (material.envMap) material.envMap.dispose();
                    material.dispose(); // Disposes any programs associated with the material
                });
            }
        }
    });
    while (scene.children.length) {
        scene.remove(scene.children[0]);
    }
}

function addLightsAndControls() {
    const scene = window.myScene;
    const camera = window.myCamera;
    const renderer = window.myRenderer;

    const ambientLight = new THREE.AmbientLight(0x404040);
    scene.add(ambientLight);
    const directionalLight = new THREE.DirectionalLight(0xffffff, 0.5);
    directionalLight.position.set(0, 1, 0);
    scene.add(directionalLight);

    const controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.update();
}

function loadNewModel(modelFileName) {
    const scene = window.myScene;
    const camera = window.myCamera;
    const renderer = window.myRenderer;
    const loader = new THREE.GLTFLoader();

    loader.load(`project_root/assets/3d_models/${modelFileName}`, function (gltf) {
        scene.add(gltf.scene);
        animate();
    }, undefined, function (error) {
        console.error('An error happened while loading the model:', error);
    });

    function animate() {
        requestAnimationFrame(animate);
        renderer.render(scene, camera);
    }
}



function viewInAR(modelFileName) {
    // Generate the full URL to the .usdz file hosted via ngrok
    const arModelUrl = `https://b1e1-202-134-172-62.ngrok-free.app/furniture/project_root/assets/3d_models/${modelFileName}.glb`;

    // Using goqr.me API to generate the QR code
    const qrCodeURL = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(arModelUrl)}`;

    // Open the QR code in a new window or modal
    const qrWindow = window.open("", "QR Code", "width=200,height=200");
    qrWindow.document.write(`<img src="${qrCodeURL}" alt="QR Code" style="width:100%; height:100%;">`);
    qrWindow.document.title = "View in AR";
}


