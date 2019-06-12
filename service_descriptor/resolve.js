// https://github.com/mohsen1/multi-file-swagger-example

const resolve = require('json-refs').resolveRefs;
const YAML = require('js-yaml');
const fs = require('fs-extra');
const beautify = require('json-beautify');

process.chdir('hirecar_api');

// resolve each endpoint as a separate service descriptor
fs.readdirSync('paths')
    .filter(file => file !== 'index.yaml')
    .map(path => path.split('.')[0])
    .forEach(path => resolve_refs(path));

// output a service descriptor for the entire API
resolve_contents(fs.readFileSync('index.yaml').toString(), 'index');

function resolve_contents(spec, name) {
    const root = YAML.safeLoad(spec);
    const options = {
        filter: ['relative', 'remote'],
        loaderOptions: {
            processContent: (res, callback) => {
                callback(null, YAML.safeLoad(res.text));
            }
        }
    };

    resolve(root, options).then(results => {
        const merged_json = beautify(results.resolved, null, 2, 80);
        const merged_yaml = YAML.safeDump(results.resolved);

        fs.outputFileSync(`../resolved/${name}.json`, merged_json);
        fs.outputFileSync(`../resolved/${name}.yaml`, merged_yaml);

        console.info(`resolved: ${name}`);
    });
}

function resolve_refs(path) {
    const file = `./paths/${path}.yaml`;

    if (!fs.existsSync(file)) {
        console.error('File does not exist: ' + file);
        process.exit(1);
    }

    const uri_path = path.substr(-3) === '_id' ? `${path.split('_id')[0]}/{id}` : path;

    const spec = `
openapi: 3.0.0
info:
    $ref: ./info/index.yaml
tags:
    $ref: ./tags/index.yaml
paths:
    /${uri_path}:
        $ref: ${file}
    `;

    resolve_contents(spec, path);
}