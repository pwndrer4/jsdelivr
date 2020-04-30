(function () {
    var canvas, gl, glRenderer, models,
        devices = {
            "Apple A4 GPU": {
                1136: ["iPhone 4"],
                1024: ["iPad"]
            },
            "Apple A5 GPU": {
                1024: ["iPad 2"],
                1136: ["iPhone 4S"],
            },
            "Apple A5X GPU": {
                2048: ["iPad 3"],
            },
            "Apple A6X GPU": {
                2048: ["iPad 4"],
            },          
            "Apple A6 GPU": {
                1024: ["iPad Pro (9.7寸)"],
                1136: ["iPhone 5C", "iPhone 5"]
            },
            "Apple A7 GPU": {
                1136: ["iPhone 5S"],
                2224: ["iPad Pro (9.7寸)"],
                2732: ["iPad Pro (12.9寸)"],
                2048: ["iPad Air", "iPad Mini 2", "iPad Mini 3"]
            },

            "Apple A8 GPU": {
                1136: ["iPod touch (6th generation)"],
                1334: ["iPhone 6"],
                2001: ["iPhone 6 Plus"],
                2048: ["iPad Mini 4"]
            },
            "Apple A8X GPU": {
                2048: ["iPad Air 2"]
            },
            "Apple A9 GPU": {
                1136: ["iPhone SE"],
                1334: ["iPhone 6S"],
                2001: ["iPhone 6S Plus"],
                2224: ["iPad Pro (9.7寸)"],
                2732: ["iPad Pro (12.9寸)"]
            },

            "Apple A10 GPU": {
                1334: ["iPhone 7"],
                2001: ["iPhone 7 Plus"],
                2048: ["iPad Air"]
            },
            "Apple A10X GPU": {
                2224: ["iPad Pro (9.7寸)"],
                2732: ["iPad Pro (12.9寸)"]
            },
			"Apple A11 GPU": {
                2436: ["iPhone X"],
                2001: ["iPhone 8 Plus"],
				1334: ["iPhone 8"]
            },
          
			"Apple A12 GPU": {
                2436: ["iPhone XS"],
                2688: ["iPhone XSMax"],
				1792: ["iPhone XR"]
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
                models = ['unknown'];
            } else {
                models = device[getScreenWidth()];

                if (models == undefined) {
                    models = ['unknown'];
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
