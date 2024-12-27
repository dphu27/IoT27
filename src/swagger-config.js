const swaggerJSDoc = require('swagger-jsdoc');

const swaggerOptions = {
    definition: {
        openapi: '3.0.0',
        info: {
            title: 'IoT Control API',
            version: '1.0.0',
            description: 'API documentation for IoT device control',
        },
        servers: [
            {
                url: 'http://localhost:4000', // URL của server
            },
        ],
    },
    apis: ['./server.js'], // Chỉ định nơi định nghĩa các endpoint (có thể thay đổi theo cấu trúc dự án)
};

const swaggerSpec = swaggerJSDoc(swaggerOptions);
module.exports = swaggerSpec;
