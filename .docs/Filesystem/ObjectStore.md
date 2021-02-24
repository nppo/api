# ObjectStore

## Introduction
This application needs flexible and scalable storage space. SURF has it's own storage service for these type of applications called SURF Object Store

## Integration
At the moment of implementing the SURF Object Store uses an OpenStack Object Storage. It was decided to use a package to interact with the storage.

## Environment Settings
- `OS_AUTH_URL` => Should always be the AUTH URL provided by SURF Object Store. By default this should be `https://proxy.swift.surfsara.nl:5000/v3`
- `OS_REGION_NAME` => The region that should be used. By default: `RegionOne`
- `OS_USERNAME` => Username used to connect with the disk, this info should be provided by SURF. No default.
- `OS_USER_DOMAIN_NAME` => Domain name according to [SURF Documentation](http://doc.swift.surfsara.nl/en/latest/Pages/Clients/how_to_use_swift.html#authentication). By default: `Default`.
- `OS_PASSWORD` => Password used to connect with the disk, this info should be provided by SURF. No default.
- `OS_CONTAINER_NAME` => The container / top level folder that should be used by the disk. By default: `public`.

## References
- [SURF Object Store](https://www.surf.nl/en/object-store-store-large-quantities-of-data)
- [SURF Object Store Documentation](https://swiftdocs.readthedocs.io/en/latest/)
- [Open Stack Documentation](https://wiki.openstack.org/wiki/Swift)
- [Package Documentation](https://github.com/mzur/laravel-openstack-swift)
