const Ziggy = {"url":"http:\/\/test.test","port":null,"defaults":{},"routes":{"ignition.healthCheck":{"uri":"_ignition\/health-check","methods":["GET","HEAD"]},"ignition.executeSolution":{"uri":"_ignition\/execute-solution","methods":["POST"]},"ignition.updateConfig":{"uri":"_ignition\/update-config","methods":["POST"]},"companies.index":{"uri":"companies","methods":["GET","HEAD"]},"companies.create":{"uri":"companies\/create","methods":["GET","HEAD"]},"companies.store":{"uri":"companies","methods":["POST"]},"companies.show":{"uri":"companies\/{company}","methods":["GET","HEAD"],"bindings":{"company":"id"}},"companies.edit":{"uri":"companies\/{company}\/edit","methods":["GET","HEAD"],"bindings":{"company":"id"}},"companies.update":{"uri":"companies\/{company}","methods":["PUT","PATCH"],"bindings":{"company":"id"}},"companies.destroy":{"uri":"companies\/{company}","methods":["DELETE"],"bindings":{"company":"id"}}}};

if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    Object.assign(Ziggy.routes, window.Ziggy.routes);
}

export { Ziggy };
