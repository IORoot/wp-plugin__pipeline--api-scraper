# Transforms

The transforms are used during the mapping process.

Each transform allows you to change the data however you require.

## Groups

The groups contain multiple transform layers that can be applied. Each layer will be cycled through and applied one after the other.

## Layers

Each layer will perform a different function to change the data in some way. The layer class will pass the configuration and data to the correct transform.

## Transforms.

Each transform class is an instance of the `transformInterface` and will alter the data in some particular way.