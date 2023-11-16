import * as THREE from 'three';
import TWEEN from 'three/addons/libs/tween.module.js';
import { TrackballControls } from 'three/addons/controls/TrackballControls.js';
import { CSS3DRenderer, CSS3DObject } from 'three/addons/renderers/CSS3DRenderer.js';
const data = window.data;
let camera, scene, renderer;
let controls;

const objects = [];
const targets = { table: [], sphere: [], helix: [], grid: [] };

init();
animate();

function init() {

    camera = new THREE.PerspectiveCamera(40, window.innerWidth / window.innerHeight, 1, 10000);
    camera.position.z = 3000;

    scene = new THREE.Scene();
    const colors = ['239, 48, 34', '253, 202, 53', '58, 159, 72', 'rgba(0,127,127,' + ( Math.random() * 0.5 + 0.25 ) + ')'];
    // table

    for (let i = 0; i < data.length; i ++) {
        const image = document.createElement('img');
        image.setAttribute('src', data[i].Photo);
        image.style.width = '100px';
        image.style.height = 'auto';

        const country = document.createElement('p');
        const age = document.createElement('p');
        const name = document.createElement('p');
        const interest = document.createElement('p');
        country.textContent = data[i].Country;
        age.textContent = data[i].Age;
        name.textContent = data[i].Name;
        interest.textContent = data[i].Interest;

        interest.style.fontSize = '9px';
        interest.style.margin = '5px 0 0 0';
        name.style.margin = '0';

        const netWorth = data[i]['Net Worth'];
        const color = (netWorth <= 100000 ? 0 : (netWorth > 100000 && netWorth < 200000 ? 1 : (netWorth > 200000 ? 2 : 3) ))
        const element = document.createElement('div');
        element.className = 'element';
        if(color != 3){
            element.style.backgroundColor = 'rgba(' + colors[color] + ', 0.3)';
            element.style.boxShadow = '0px 0px 12px rgba(' + colors[color] + ', 0.5)';
            element.style.border = '1px solid rgba(' + colors[color] + ', 0.5)';

        }else{
            element.style.backgroundColor = colors[color];
        }

        const number = document.createElement('div');
        number.className = 'number';
        number.style.display = 'flex';
        number.style.flexDirection = 'row';
        number.style.margin = '0px 20px';
        number.style.justifyContent = 'space-between';

        number.appendChild(country);
        number.appendChild(age);
        element.appendChild(number);

        const symbol = document.createElement('div');
        symbol.className = 'symbol';
        symbol.appendChild(image);
        element.appendChild(symbol);

        const details = document.createElement('div');
        details.className = 'details';
        details.appendChild(name);
        details.appendChild(interest);
        element.appendChild(details);

        const objectCSS = new CSS3DObject(element);
        objectCSS.position.x = Math.random() * 4000 - 2000;
        objectCSS.position.y = Math.random() * 4000 - 2000;
        objectCSS.position.z = Math.random() * 4000 - 2000;
        scene.add(objectCSS);
        objects.push(objectCSS);

        //

        const object = new THREE.Object3D();
        object.position.x = (data[i].j * 140) - 1330;
        object.position.y = - (data[i].i * 180) + 990;
        console.log(data[i].i + ', ' + data[i].j);
        targets.table.push(object);

    }

    // sphere

    const vector = new THREE.Vector3();

    for (let i = 0, l = objects.length; i < l; i++) {

        const phi = Math.acos(- 1 + (2 * i) / l);
        const theta = Math.sqrt(l * Math.PI) * phi;

        const object = new THREE.Object3D();

        object.position.setFromSphericalCoords(800, phi, theta);

        vector.copy(object.position).multiplyScalar(2);

        object.lookAt(vector);

        targets.sphere.push(object);

    }

    // helix

    for (let i = 0, l = objects.length; i < l; i++) {

        const theta = i * 1 + Math.PI;
        const y = - (i * 8) + 450;

        const object = new THREE.Object3D();

        object.position.setFromCylindricalCoords(900, theta, y);

        vector.x = object.position.x * 2;
        vector.y = object.position.y;
        vector.z = object.position.z * 2;

        object.lookAt(vector);

        targets.helix.push(object);

    }

    // grid

    for (let i = 0; i < objects.length; i++) {

        const object = new THREE.Object3D();

        object.position.x = ((i % 5) * 400) - 800;
        object.position.y = (- (Math.floor(i / 5) % 4) * 400) + 800;
        object.position.z = (Math.floor(i / 20)) * 1000 - 2000;

        targets.grid.push(object);

    }

    //

    renderer = new CSS3DRenderer();
    renderer.setSize(window.innerWidth, window.innerHeight);
    document.getElementById('container').appendChild(renderer.domElement);

    //

    controls = new TrackballControls(camera, renderer.domElement);
    controls.minDistance = 500;
    controls.maxDistance = 6000;
    controls.addEventListener('change', render);

    const buttonTable = document.getElementById('table');
    buttonTable.addEventListener('click', function () {

        transform(targets.table, 2000);

    });

    const buttonSphere = document.getElementById('sphere');
    buttonSphere.addEventListener('click', function () {

        transform(targets.sphere, 2000);

    });

    const buttonHelix = document.getElementById('helix');
    buttonHelix.addEventListener('click', function () {

        transform(targets.helix, 2000);

    });

    const buttonGrid = document.getElementById('grid');
    buttonGrid.addEventListener('click', function () {

        transform(targets.grid, 2000);

    });

    transform(targets.table, 2000);

    //

    window.addEventListener('resize', onWindowResize);

}

function transform(targets, duration) {

    TWEEN.removeAll();

    for (let i = 0; i < objects.length; i++) {

        const object = objects[i];
        const target = targets[i];

        new TWEEN.Tween(object.position)
            .to({ x: target.position.x, y: target.position.y, z: target.position.z }, Math.random() * duration + duration)
            .easing(TWEEN.Easing.Exponential.InOut)
            .start();

        new TWEEN.Tween(object.rotation)
            .to({ x: target.rotation.x, y: target.rotation.y, z: target.rotation.z }, Math.random() * duration + duration)
            .easing(TWEEN.Easing.Exponential.InOut)
            .start();

    }

    new TWEEN.Tween(this)
        .to({}, duration * 2)
        .onUpdate(render)
        .start();

}

function onWindowResize() {

    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();

    renderer.setSize(window.innerWidth, window.innerHeight);

    render();

}

function animate() {

    requestAnimationFrame(animate);

    TWEEN.update();

    controls.update();

}

function render() {

    renderer.render(scene, camera);

}