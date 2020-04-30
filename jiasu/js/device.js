(function () {
    var canvas, gl, glRenderer, models,
        devices = {
            "Apple A4 GPU": {
                1136: ["iPhone 4 32位设备"],
                1024: ["iPad 32位设备"]
            },
            "Apple A5 GPU": {
                1024: ["iPad 2 32位设备"],
                1136: ["iPhone 4S 32位设备"],
            },
            "Apple A5X GPU": {
                2048: ["iPad 3 32位设备"],
            },
            "Apple A6X GPU": {
                2048: ["iPad 4 32位设备"],
            },          
            "Apple A6 GPU": {
                1024: ["iPad Pro (9.7寸)"],
                1136: ["iPhone 5C 32位设备", "iPhone 5 32位设备"]
            },
            "Apple A7 GPU": {
                1136: ["iPhone 5S 64位设备"],
                2224: ["iPad Pro (9.7寸) 64位设备"],
                2732: ["iPad Pro (12.9寸) 64位设备"],
                2048: ["iPad Air 64位设备", "iPad Mini 2 64位设备", "iPad Mini 3 64位设备"]
            },

            "Apple A8 GPU": {
                1136: ["iPod touch (6th generation)"],
                1334: ["iPhone 6 64位设备"],
                2208: ["iPhone 6 Plus 64位设备"],
                2048: ["iPad Mini 4 64位设备"]
            },
            "Apple A8X GPU": {
                2048: ["iPad Air 2 64位设备"]
            },
            "Apple A9 GPU": {
                1136: ["iPhone SE 64位设备"],
                1334: ["iPhone 6S 64位设备"],
                1472: ["iPhone 6S 64位设备"],
                2208: ["iPhone 6S Plus 64位设备"],
                2224: ["iPad Pro (9.7寸) 64位设备"],
                2732: ["iPad Pro (12.9寸) 64位设备"]
            },

            "Apple A10 GPU": {
                1334: ["iPhone 7 64位设备"],
                2208: ["iPhone 7 Plus 64位设备"],
                1920: ["iPhone 8 Plus 64位设备"],
                2048: ["iPad Air 64位设备"]
            },
            "Apple A10X GPU": {
                2224: ["iPad Pro (9.7寸) 64位设备"],
                2732: ["iPad Pro (12.9寸) 64位设备"]
            },
			"Apple A11 GPU": {
                2436: ["iPhone X 64位设备"],
                2208: ["iPhone 8 Plus 64位设备"],
                1920: ["iPhone 8 Plus 64位设备"],
				1334: ["iPhone 8 64位设备"]
            },
          
			"Apple A12 GPU": {
                2436: ["iPhone XS 64位设备"],
                2688: ["iPhone XSMax 64位设备"],
				1792: ["iPhone XR 64位设备"]
            }
        };

    function getCanvas() {
        if (canvas == null) {
            canvas = document.createElement('canvas');
        }

        return canvas;
    }

    function getGl() {
        if (gl == null) {
            gl = getCanvas().getContext('experimental-webgl');
        }

        return gl;
    }

    function getScreenWidth() {
        return Math.max(screen.width, screen.height) * (window.devicePixelRatio || 1);
    }

    function getGlRenderer() {
        if (glRenderer == null) {
            debugInfo = getGl().getExtension('WEBGL_debug_renderer_info');
            glRenderer = debugInfo == null ? 'unknown' : getGl().getParameter(debugInfo.UNMASKED_RENDERER_WEBGL);
        }

        return glRenderer;
    }

    function getModels() {
        if (models == null) {
            var device = devices[getGlRenderer()];

            if (device == undefined) {
                models = ['未检测到机型'];
            } else {
                models = device[getScreenWidth()];

                if (models == undefined) {
                    models = ['未检测到机型'];
                }
            }
        }

        return models;
    }

    if (window.MobileDevice == undefined) {
        window.MobileDevice = {};
    }

    window.MobileDevice.getGlRenderer = getGlRenderer;
    window.MobileDevice.getModels = getModels;
})();
