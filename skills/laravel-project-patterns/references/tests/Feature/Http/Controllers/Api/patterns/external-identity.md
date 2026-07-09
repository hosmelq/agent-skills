# External Identity Endpoints

## When To Use

Use this leaf for external identity verification and account-linking scenarios.

## Pattern

### External Identity Endpoints

For external identity endpoints:

- fake token/key verification through the mechanism used by equivalent live
  siblings for the same provider client;
- cover verification failures such as invalid token, invalid audience, expired token, invalid issuer, and nonce mismatch when supported;
- cover existing identity login;
- cover missing required claims when the controller handles that branch;
- cover same external ID with changed email;
- cover existing email conflicts;
- cover external ID conflicts;
- cover account creation;
- assert provider fields, token count, and verified email state when relevant;
- assert the response token is the client token without Sanctum's storage-ID
  prefix when exact token content is part of the contract.

## Related References

- [Parent router](../README.md)
